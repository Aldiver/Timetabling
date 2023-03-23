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
        // $currentGradelevel = $schoolprogram->getGroup(3); //delete this later
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
                        } elseif (!(in_array($groupedTimeslots[1][2], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher])) && !$timetable->withOhsp($randomTeacher)) {
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

                            if ($noClonflict && !$timetable->withOhsp($randomTeacher)) {
                                array_push($this->teacher_loading[$group->getId()][$toInsert][$randomTeacher], ...$timeslotIds);
                                foreach ($timeslotIds as $ts) {
                                    $classes[$section][$toInsert][$randomTeacher][] = $ts;
                                }
                                unset($groupedTimeslots[1]);
                                $teacherId2 = $randomTeacher;
                                break;
                            }
                        }
                        //insert last
                        foreach ($groupedTimeslots as $key => $timeslot) {
                            if (count($timeslot) == 5) {
                                $insertLast = $randomDepartment == 6 ? $teacherId : $teacherId2;
                                $attempts = 50;
                                $rand = array_rand($timeslot);
                                if ($timetable->withOhsp($insertLast)) {
                                    $copy = $timeslot;
                                    $data = array_pop($copy);
                                    shuffle($copy);
                                } else {
                                    shuffle($timeslot);
                                }

                                foreach ($timeslot as $tsId => $ts) {
                                    if (!in_array($timeslot[$rand], $this->teacher_loading[$group->getId()][6][$insertLast])) {
                                        $rand = $tsId;
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
                        if (!(in_array($groupedTimeslots[1][0], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher])) && !$timetable->withOhsp($randomTeacher)) {
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
                $randomTimeslot = $groupedTimeslots->keys()->random();
                if (!(count($groupedTimeslots[$i]) == 5)) {
                    //get 4 meetings subjects
                    $filteredDeps = $departments->reject(fn ($item) => $item === 6 || $item ===8);
                    $groupId = $group->getId();
                    $sectionCount = count($group->getSectionIds());
                    // $filteredDepsSorted = $filteredDeps->sortBy(function ($id) use ($groupId) {
                    //     return count($this->teacher_loading[$groupId][$id]);
                    // });

                    $filteredDepsSorted = $filteredDeps->sortByDesc(function ($id) use ($groupId, $sectionCount) {
                        $count = 0;
                        foreach ($this->teacher_loading[$groupId][$id] as $teacher) {
                            $count += count($teacher);
                        }
                        return $count / (4*$sectionCount);
                    });

                    $filteredDeps->shuffle();
                    foreach ($filteredDeps as $randomDepartment) {
                        //check then get
                        $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$randomDepartment]);
                        $copyTS = $groupedTimeslots[$i];
                        $copyTS = array_pop($copyTS);
                        foreach ($teacher as $randomTeacher) {
                            $timeslotIds = null;
                            $randomPeriods = array_rand($groupedTimeslots[$i], 2);

                            // dd(((strpos($copyTS, "D5") !== false) && !$timetable->withOhsp($randomTeacher)), (strpos($copyTS, "D5") !== false), !$timetable->withOhsp($randomTeacher), $copyTS);
                            // dd($groupedTimeslots[$randomTimeslot][3]);
                            //if may friday sa timeslot tapos yung teacher is not OHSP
                            if (!((strpos($copyTS, "D5") !== false) && $timetable->withOhsp($randomTeacher))) {
                                // dd($randomTeacher, $copyTS, $timetable->teacheCheck());
                                if (!(in_array($groupedTimeslots[$i][$randomPeriods[0]], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher])) &&
                                !(in_array($groupedTimeslots[$i][$randomPeriods[1]], $this->teacher_loading[$group->getId()][$randomDepartment][$randomTeacher]))
                                ) {
                                    $timeslotIds = array_values($groupedTimeslots[$i]);
                                    $teacherId = $randomTeacher;
                                    break;
                                } else {
                                    $timeslotIds = false;
                                }
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

                            if ($noClonflict && !$timetable->withOhsp($randomTeacher)) {
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
        $newChromosome = array_merge(...$classes);
        $this->chromosome = $newChromosome;
        $this->check();
        // dd($this->teacher_loading, $timetable->teacheCheck(), $classes, $this->clashes);
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
                    array_push($checkConflictBySection, ...$ts);
                }
            }
            if (count($checkConflictBySection) < 29) {
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
}
