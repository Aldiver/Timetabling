<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Http\Services\ClassData;
use App\Http\Services\Subject;
use App\Http\Services\Period;
use App\Models\Teacher;

class Individual
{
    private $schoolprogram;
    private $fitness;
    private $chromosome;
    private $id;
    private $conflicts;
    private $teacher_conflicts;
    private $fitnessChanged = true;
    private $teacher_loading = [];
    private $bySection;
    private $clashes;
    private $gl;

    public function __construct($schoolprogram = null, $currentGradelevel = null)
    {
        $this->schoolprogram = $schoolprogram;
        $this->gl = $currentGradelevel;
        if ($schoolprogram) {
            foreach ($currentGradelevel->getModuleIds() as $moduleId) {
                $module = $schoolprogram->getModule($moduleId, $currentGradelevel->getId());
                foreach ($module->getTeacherIds() as $teacherId) {
                    if (!isset($this->teacher_loading[$currentGradelevel->getId()][$moduleId][$teacherId])) {
                        $this->teacher_loading[$currentGradelevel->getId()][$moduleId][$teacherId] = [];
                    }
                }
            }
            $this->generateSchedule($schoolprogram, $currentGradelevel);
        } else {
            $this->chromosome = [];
        }
    }

    public function generateSchedule($timetable, $currentGradelevel)
    {
        //Initialize constants for each class schedules
        $newChromosome = [];
        $chromosomeIndex = 0;
        $group = $currentGradelevel;
        $class = [];
        $retrySection = false;


        foreach ($group->getSectionIds() as $section) {
            //init
            foreach ($group->getModuleIds() as $moduleKey) {
                $classes[$section][$moduleKey] = [];
            }
            $timetable->copyTimeslot();
            $timetable->reserveTimeslots();
            $departments = collect($group->getModuleIds())->shuffle();
            $groupedTimeslots = collect($timetable->getGroupedTimeslots());
            //for advisory
            $MODULES = ($group->getId() > 2) ? $departments->reject(fn ($item) => $item === 7) : $departments;

            foreach ($MODULES as $randomDepartment) {
                if ($randomDepartment == 6 || $randomDepartment == 8) {
                    $toInsert = ($randomDepartment == 6) ? 8 : 6;
                    //get meeting times for dep
                    $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$randomDepartment]);

                    foreach ($teacher as $randomTeacher) {
                        $timeslotIds = [];
                        if (!(in_array($groupedTimeslots[1][0], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))) {
                            $timeslotIds[] = $groupedTimeslots[1][0];
                            $timeslotIds[] = $groupedTimeslots[1][1];
                            $teacherId = $randomTeacher;
                            break;
                        } elseif (!(in_array($groupedTimeslots[1][2], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))) {
                            $timeslotIds[] = $groupedTimeslots[1][2];
                            $timeslotIds[] = $groupedTimeslots[1][3];
                            $teacherId = $randomTeacher;
                            break;
                        } else {
                            $timeslotIds = false;
                        }
                    }

                    if ($timeslotIds) {
                        array_push($this->teacher_loading[$group->getId()][$randomDepartment][$teacherId], ...$timeslotIds);
                        foreach ($timeslotIds as $ts) {
                            $classes[$section][$randomDepartment][$teacherId][] = $ts;
                        }
                        $groupedTimeslots[1] = array_diff($groupedTimeslots[1], $timeslotIds);

                        $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$toInsert]);
                        $timeslotIds = [];

                        foreach ($teacher as $randomTeacher) {
                            $timeslotIds = [];
                            $noClonflict = true;
                            foreach ($groupedTimeslots[1] as $lastTimeslots) {
                                if (in_array($lastTimeslots, $this->teacher_loading[$group->getId()][$toInsert][$randomTeacher])) {
                                    $noConflict = false;
                                }
                                $timeslotIds[] = $lastTimeslots;
                            }

                            if ($noClonflict) {
                                array_push($this->teacher_loading[$group->getId()][$toInsert][$randomTeacher], ...$timeslotIds);
                                foreach ($timeslotIds as $ts) {
                                    $classes[$section][$toInsert][$randomTeacher][] = $ts;
                                }
                                unset($groupedTimeslots[1]);
                                $teacherId2 = $randomTeacher;
                                break;
                            }
                        }

                        // foreach ($teacher as $randomTeacher) {
                        //     if (!(in_array($groupedTimeslots[1], $this->teacher_loading[$group->getId()][$toInsert][$randomTeacher]))) {
                        //         $timeslotIds = $groupedTimeslots[1];
                        //         array_push($this->teacher_loading[$group->getId()][$toInsert][$randomTeacher], ...$timeslotIds);
                        //         unset($groupedTimeslots[1]);
                        //         foreach ($timeslotIds as $ts) {
                        //             $classes[$section][$toInsert][$randomTeacher][] = $ts;
                        //         }

                        //         $teacherId2 = $randomTeacher;
                        //         break;
                        //     }
                        // }

                        //insert last
                        foreach ($groupedTimeslots as $key => $timeslot) {
                            if (count($timeslot) == 5) {
                                $rand = array_rand($timeslot);
                                $insertLast = $randomDepartment == 6 ? $teacherId : $teacherId2;
                                $attempts = 50;
                                while (in_array($timeslot[$rand], $this->teacher_loading[$group->getId()][6][$insertLast])) {
                                    $attempts--;
                                    $rand = array_rand($timeslot);
                                    if ($attempts == 0) {
                                        $rand = 0;
                                        break;
                                    }
                                }
                                array_push($this->teacher_loading[$group->getId()][6][$insertLast], $timeslot[$rand]);
                                $classes[$section][6][$insertLast][] = $timeslot[$rand];
                                $groupedTimeslots[$key] = array_diff($groupedTimeslots[$key], [$timeslot[$rand]]);
                                // dd($randomDepartment, $key, $rand, $groupedTimeslots[$key], $timeslot);
                                break;
                            }
                        }
                        $departments = $departments->reject(function ($value) {
                            return $value == 6 || $value == 8;
                        });
                    }
                } else {
                    $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$randomDepartment]);
                    foreach ($teacher as $randomTeacher) {
                        $timeslotIds = null;
                        if (!(in_array($groupedTimeslots[1][0], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))) {
                            $timeslotIds = $groupedTimeslots[1];
                            $teacherId = $randomTeacher;
                            break;
                        } else {
                            $timeslotIds = false;
                        }
                    }

                    if ($timeslotIds) {
                        array_push($this->teacher_loading[$group->getId()][$randomDepartment][$teacherId], ...$timeslotIds);
                        foreach ($timeslotIds as $ts) {
                            $classes[$section][$randomDepartment][$teacherId][] = $ts;
                        }
                        unset($groupedTimeslots[1]);

                        $departments = $departments->reject(fn ($item) => $item === $randomDepartment);
                    }

                    //code
                }
                // dd($randomDepartment, $this->teacher_loading);

                if ($timeslotIds) {
                    break;
                }
            }

            //non advisory slots

            for ($i = 2; $i <= 7; $i++) {
                $randomTimeslot = $groupedTimeslots->random();
                if (!(count($groupedTimeslots[$i]) == 5)) {
                    //get 4 meetings subjects
                    $filteredDeps = $departments->reject(fn ($item) => $item === 6 || $item ===8);
                    $groupId = $group->getId();
                    $filteredDepsSorted = $filteredDeps->sortBy(function ($id) use ($groupId) {
                        return count($this->teacher_loading[$groupId][$id]);
                    });
                    $filteredDeps->shuffle();
                    foreach ($filteredDeps as $randomDepartment) {
                        //check then get
                        $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$randomDepartment]);
                        foreach ($teacher as $randomTeacher) {
                            $timeslotIds = null;
                            $randomPeriods = array_rand($groupedTimeslots[$i], 2);
                            if (!(in_array($groupedTimeslots[$i][$randomPeriods[0]], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher])) &&
                            !(in_array($groupedTimeslots[$i][$randomPeriods[1]], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))) {
                                $timeslotIds = array_values($groupedTimeslots[$i]);
                                $teacherId = $randomTeacher;
                                break;
                            } else {
                                $timeslotIds = false;
                            }
                        }

                        if ($timeslotIds) {
                            array_push($this->teacher_loading[$group->getId()][$randomDepartment][$teacherId], ...$timeslotIds);
                            foreach ($timeslotIds as $ts) {
                                $classes[$section][$randomDepartment][$teacherId][] = $ts;
                            }
                            unset($groupedTimeslots[$i]);

                            $departments = $departments->reject(fn ($item) => $item == $randomDepartment);
                            break;
                        }
                    }
                } else {
                    //get s/ESP
                    $newfilteredDeps = $departments->filter(fn ($item) => $item === 6 || $item === 8);

                    $randomDepartment = $newfilteredDeps->random();
                    $toInsert = ($randomDepartment == 6) ? 8 : 6;
                    $slots = ($randomDepartment == 6) ? 3 : 2;
                    $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$randomDepartment]);
                    foreach ($teacher as $randomTeacher) {
                        $timeslotIds = null;

                        for ($j = 0; $j < $slots; $j++) {
                            if (!(in_array($groupedTimeslots[$i][$j], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))) {
                                $timeslotIds[] = $groupedTimeslots[$i][$j];
                            }
                        }
                        $teacherId1 = $randomTeacher;
                        if ($timeslotIds && (count($timeslotIds) === $slots)) {
                            break;
                        }
                    }

                    if ($timeslotIds) {
                        array_push($this->teacher_loading[$group->getId()][$randomDepartment][$teacherId1], ...$timeslotIds);
                        foreach ($timeslotIds as $ts) {
                            $classes[$section][$randomDepartment][$teacherId1][] = $ts;
                        }
                        $groupedTimeslots[$i] = array_diff($groupedTimeslots[$i], $timeslotIds);
                        $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$toInsert]);

                        foreach ($teacher as $randomTeacher) {
                            $timeslotIds = [];
                            $noClonflict = true;
                            foreach ($groupedTimeslots[$i] as $lastTimeslots) {
                                $timeslotIds[] = $lastTimeslots;
                                if (in_array($lastTimeslots, $this->teacher_loading[$group->getId()][$toInsert][$randomTeacher])) {
                                    $noConflict = false;
                                }
                            }

                            if ($noClonflict) {
                                array_push($this->teacher_loading[$group->getId()][$toInsert][$randomTeacher], ...$timeslotIds);
                                foreach ($timeslotIds as $ts) {
                                    $classes[$section][$toInsert][$randomTeacher][] = $ts;
                                }
                                unset($groupedTimeslots[$i]);
                                break;
                            }
                        }

                        $departments->reject(fn ($item) => $item === $randomDepartment || $item === $toInsert);
                        //insert next subject
                    }
                }
            }
            $timetable->reset();
        }
        //last
        $this->bySection = $classes;
        // dd($this->teacher_loading, $classes);
        $newChromosome = array_merge(...$classes);
        $this->chromosome = $newChromosome;
    }

    public function check()
    {
        $toCheck = $this->teacher_loading;
        $sectionSchedule = $this->bySection;
        $clashes = 0;

        foreach ($toCheck[$this->gl->getId()] as $departments) {
            foreach ($departments as $teacher) {
                $temp = array_unique($teacher);
                if ($teacher !== $temp) {
                    $clashes++;
                    break;
                }
            }
        }

        foreach ($sectionSchedule as $section) {
            if (count($section) < 8) {
                $clashes++;
            }
            $checkConflictBySection = [];
            foreach ($section as $class) {
                foreach ($class as $ts) {
                    // $checkConflictBySection[] = $ts;
                    array_push($checkConflictBySection, ...$ts);
                }
            }
            if (count($checkConflictBySection) < 29) {
                // dd($this->teacher_loading, $this->chromosome);
                $clashes++;
            }
            $temp = array_unique($checkConflictBySection);
            if ($checkConflictBySection !== $temp) {
                $clashes++;

                break;
            }
        }

        $this->clashes =  $clashes;
    }

    public function getClashes()
    {
        return $this->clashes;
    }


    /**
     * Create a new individual with a randomised chromosome
     *
     * @param int $chromosomeLength Desired chromosome length
     */
    public static function random($chromosomeLength)
    {
        $individual = new Individual();

        for ($i = 0; $i < $chromosomeLength; $i++) {
            $individual->setGene($i, mt_rand(0, 1));
        }

        return $individual;
    }

    /**
     * Get the individual's chromosome
     *
     * @return array The chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get the length of the individual's chromosome
     *
     * @return int The length
     */
    public function getChromosomeLength()
    {
        return count($this->chromosome);
    }

    /**
     * Fix a gene at the given location of the chromosome
     *
     * @param int $index The location to insert the gene
     * @param int $gene The gene
     */
    public function setGene($index, $gene)
    {
        $this->chromosome[$index] = $gene;
    }

    /**
     * Get the gene at the specified location
     *
     * @param $index The location to get the gene at
     * @return int The bit representing the gene at that location
     */
    public function getGene($index)
    {
        return $this->chromosome[$index];
    }

    /**
     * Set the fitness param for this individual
     *
     * @param double $fitness The fitness of this individual
     */

    public function toArray()
    {
        return [
            'schoolprogram' => $this->schoolprogram,
            'fitness' => $this->fitness,
            'chromosome' => $this->chromosome,
            'id' => $this->id,
            'teacher_loading' => $this->teacher_loading,
            'conflicts' => $this->conflicts,
            'teacher_conflicts' => $this->teacher_conflicts,
        ];
    }

    private function getFilteredTeachers($teachersPerModule)
    {
        $teacher_loading = collect($teachersPerModule);

        uasort($teachersPerModule, function ($a, $b) {
            $countA = count($a);
            $countB = count($b);

            if ($countA == $countB) {
                return rand(-1, 1);
            }

            return $countA > $countB ? 1 : -1;
        });

        return collect($teachersPerModule)->keys();
        $sortedTeachers = $teacher_loading->sortBy(function ($periods) {
            return count($periods);
        });

        $groupedTeachers = $sortedTeachers->groupBy(function ($periods) {
            return count($periods);
        }, $preserveKeys = true);

        // foreach ($groupedTeachers as $group) {
        //     if ($group->count() > 1) {
        //         $shuffledKeys = $group->keys()->shuffle();
        //         $shuffledGroup = $shuffledKeys->combine($group->values());
        //         dd($shuffledGroup);
        //     }
        // }

        $shuffledGroups = collect();

        foreach ($groupedTeachers as $group) {
            if ($group->count() > 1) {
                $shuffledKeys = $group->keys()->shuffle();
                $shuffledGroup = $shuffledKeys->combine($group->values());
                $shuffledGroups = $shuffledGroups->merge($shuffledGroup);
            } else {
                $shuffledGroups = $shuffledGroups->merge($group);
            }
        }
        return $groupedTeachers->collapse()->keys();
    }

    private function assignAdminLoads($teachers, $adminLoads)
    {
        $teachers = $teachers->shuffle();
        // dd($teachers);
        while ($adminLoads->isNotEmpty()) {
            $randomTeacher = $teachers->random();
            if (array_key_exists($randomTeacher->id, $this->teacher_loading)) {
                if (!in_array('Advisory', $this->teacher_loading[$randomTeacher->id])) {
                    array_push($this->teacher_loading[$randomTeacher->id], $adminLoads->shift());
                    foreach ($teachers as $key => $value) {
                        if ($randomTeacher == $value) {
                            $teachers->forget($key);
                        }
                    }
                }
            } else {
                $this->teacher_loading[$randomTeacher->id] = [$adminLoads->shift()];
                foreach ($teachers as $key => $value) {
                    if ($randomTeacher == $value) {
                        $teachers->forget($key);
                    }
                }
            }
        }
        // dd($this->teacher_loading);
    }

    private function assignRegularLoads($teacher, $load)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            array_push($this->teacher_loading[$teacher], $load);
        } else {
            $this->teacher_loading[$teacher] = [$load];
        }
    }

    private function checkIfAdvisor($teacher)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            if (in_array('Advisory', $this->teacher_loading[$teacher])) {
                return true;
            }
        }

        return false;
    }

    private function loadCount($teacher)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            return count($this->teacher_loading[$teacher]);
        }
        return 0;
    }

    private function assignOHSPLoads()
    {
        $departments = $this->schoolprogram->departments->shuffle();

        $advisoryLoad = collect(['Advisory', 'Advisory', 'Advisory', 'Advisory']);
        $ohspLoad = "OHSP"; //get model
        $teachers = [];
        $gradelevel = 0;

        foreach ($departments as $department) {
            $teacher1 = $department->teachers()
            ->whereIn('gradelevel_id', [1 + $gradelevel])->inRandomOrder()->first();

            $teacher2 = $department->teachers()
            ->whereIn('gradelevel_id', [3 + $gradelevel])->inRandomOrder()->first();

            if ($advisoryLoad->isNotEmpty()) {
                $this->teacher_loading[$teacher1->id] = [$advisoryLoad->shift(), $ohspLoad, $ohspLoad];
                $this->teacher_loading[$teacher2->id] = [$advisoryLoad->shift(), $ohspLoad, $ohspLoad];
            } else {
                $this->teacher_loading[$teacher1->id] = [$ohspLoad, $ohspLoad];
                $this->teacher_loading[$teacher2->id] = [$ohspLoad, $ohspLoad];
            }
            $gradelevel = ($gradelevel === 0) ? 1 : 0;
        }
        // dd($this->teacher_loading);
    }

    private function createScheduleBlocks($periodCount, $classdayCount)
    {
        $reservedClassdays = [];

        $reservedClassdays[0][0] = true;  // Reserve first classday of first period
        $reservedClassdays[$periodCount-1][$classdayCount-1] = true;  // Reserve last classday of last period

        // Reserve random classdays between first and last periods
        $reservedDays = [];
        $reservedperiods = [];
        for ($i = 0; $i < 4; $i++) {
            $randomPeriodIndex = mt_rand(1, $periodCount - 2);
            $randomClassdayIndex = mt_rand(0, $classdayCount - 1);

            if (!isset($reservedClassdays[$randomPeriodIndex][$randomClassdayIndex])
            && !in_array($randomClassdayIndex, $reservedDays)
            && !in_array($randomPeriodIndex, $reservedperiods)) {
                $reservedDays [] = $randomClassdayIndex;
                $reservedperiods [] = $randomPeriodIndex;
                $reservedClassdays[$randomPeriodIndex][$randomClassdayIndex] = true;
            } else {
                // If the block is already reserved, decrement $i and try again
                $i--;
            }
        }

        return $reservedClassdays;
    }

    private function checkAvailability($scheduleBlock, $period, $classdayCount)
    {
        $count = 0;
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period)][$i])) {
                $count++;
            }
        }
        if ($count==5) {
            return true;
        }
        return false;
    }

    private function checkPeriodFull($scheduleBlock, $period, $classdayCount)
    {
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period)][$i])) {
                return false;
            }
        }
        return true;
    }

    private function getNumbOfConflicts()
    {
        return $this->conflicts;
    }

    public function setFitness($fitness)
    {
        $this->fitness = $fitness;
    }

    public function getFitness()
    {
        return $this->fitness;
    }

    public function getBySection()
    {
        return $this->bySection;
    }

    public function getTeacherLoading()
    {
        return $this->teacher_loading;
    }

        /**
     * Get a printout of the individual
     *
     * @return string Output of the individual details
     */
    public function __toString()
    {
        return $this->getChromosomeString();
    }

    public function getChromosomeString()
    {
        // $flatArray = array_merge(...$this->chromosome);
        return implode(",", ...$this->chromosome);
    }

    public function save()
    {
        //save schedule to database
    }
}
