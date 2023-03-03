<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Http\Services\ClassData;
use App\Http\Services\Subject;
use App\Http\Services\Period;
use App\Models\Teacher;

class IndividualCopy
{
    private $schoolprogram;
    private $fitness;
    private $chromosome;
    private $id;
    private $conflicts;
    private $teacher_conflicts;
    private $fitnessChanged = true;
    private $teacher_loading = [];

    public function __construct($schoolprogram = null)
    {
        $this->schoolprogram = $schoolprogram;
        if ($schoolprogram) {
            $this->generateSchedule($schoolprogram);
        } else {
            $this->chromosome = [];
        }
    }

    public function generateSchedule($data)
    {
        // ini_set('max_execution_time', '300');
        //Initialize constants for each class schedules
        $newChromosome = [];
        $this->teacher_loading = [];
        $classID = 0;
        $teachersId =  $data->teachers()->pluck('id');
        $classdayCount = $data->classdays->count();
        $periodCount = $data->periods->count();
        $periods = $data->periods()->with('timeslot')->get();
        //Assign admin task and OHSP randomly to teachers'
        // $admin = $data->admins;
        $admin = ['Admin1', 'Admin2', 'Admin3', 'Admin4', 'Admin5', 'Admin6', 'Admin7', 'Admin8', 'Admin9', 'Admin10'];

        // $this->assignOHSPLoads();
        // $this->assignAdminLoads($data->teachers, collect($admin));

        foreach ($data->gradelevels as $gradelevel) {
            // $gradelevel = $data->gradelevels->find(1);
            $departmentsToCut = $data->departments;
            $advisoryPool = collect(); // create a new empty collection

            foreach ($departmentsToCut as $currentDepartment) {
                $count = ($this->getFilteredTeachers($gradelevel, $currentDepartment, false))->count();

                // Insert the $currentDepartment to the $advisoryPool collection by N times where N = $count
                for ($i = 0; $i < $count; $i++) {
                    $advisoryPool->push($currentDepartment);
                }
            }

            foreach ($gradelevel->sections as $section) {
                //create a class schedule for each section
                $class = new ClassData($classID++, $gradelevel, $section);
                $reservedClassdays = $this->createScheduleBlocks($periodCount, $classdayCount);
                $departments = $data->departments;
                $periodIndex = 1;

                //section unique variables

                $periodsWithVacantSlot = [];
                $advisory = false;
                $unsetFlag = false;

                foreach ($periods as $period) {
                    if ($periodIndex == 1) {
                        $advisory = true;
                        if ($gradelevel->id > 2) {
                            $filteredDepartments = $departments->whereIn('name', $advisoryPool->pluck('name'));
                            $departmentsAdvisory = $filteredDepartments->filter(function ($department) {
                                return $department->name !== "TLE DEPARTMENT";
                            });
                            $selectedDepartment = $departmentsAdvisory->random();

                            if ($advisoryPool->count() > 1) {
                                $indexToRemove = $advisoryPool->search(function ($item) use ($selectedDepartment) {
                                    return $item->name === $selectedDepartment->name;
                                });
                                $advisoryPool->splice($indexToRemove, 1);
                            }
                        } else {
                            //fix advisory pool
                            $filteredDepartments = $departments->whereIn('name', $advisoryPool->pluck('name'));

                            // Select a random item from the filtered collection
                            $selectedDepartment = $filteredDepartments->random();

                            // Remove one item from the $advisoryPool collection
                            if ($advisoryPool->count() > 1) {
                                $indexToRemove = $advisoryPool->search(function ($item) use ($selectedDepartment) {
                                    return $item->name === $selectedDepartment->name;
                                });
                                $advisoryPool->splice($indexToRemove, 1);
                            }
                        }
                    } elseif ($this->checkAvailability($reservedClassdays, $periodIndex - 1, $classdayCount)) {
                        // dd($reservedClassdays, $periodIndex-1);
                        $departmentsToInsert = $departments->filter(function ($department) {
                            return $department->name === "ARPAN DEPARTMENT" || $department->name === "EDUKASYON SA PAGPAPAHALAGA DEPARTMENT";
                        });
                        //if arpan and esp is not yet assigned
                        if ($departmentsToInsert->count() == 2) {
                            //get either of the two as selectedDepartment, then store
                            $selectedDepartment = $departmentsToInsert->random();

                            $reservedSubject = $departmentsToInsert->reject(function ($department) use ($selectedDepartment) {
                                return $department->id === $selectedDepartment->id;
                            })->first();
                            //remove reserved subject from pool
                            $departments = $departments->reject(function ($department) use ($reservedSubject) {
                                return $department->id === $reservedSubject->id;
                            });
                        //insert subject
                        //save remaining values
                        } else {
                            $reservedSubject = $departmentsToInsert->first();
                            if (!$reservedSubject) {
                                dd($reservedClassdays, $reservedSubject, $this->teacher_loading);
                            }// dd($reservedSubject);
                            $departments = $departments->reject(function ($department) use ($reservedSubject) {
                                return $department->id === $reservedSubject->id;
                            });
                            //set one slot in random order
                            $unsetClassday = mt_rand(0, $classdayCount-1);
                            $unsetPeriod = $periodIndex - 1;
                            $periodsWithVacantSlot[] = $periodIndex-1;
                            $reservedClassdays[$periodIndex-1][$unsetClassday][] = "SET";
                            $unsetFlag = true;
                            $selectedDepartment = $departments->random();
                        }
                    } else {
                        $departmentsFullLoads = $departments->filter(function ($department) {
                            return $department->name !== "ARPAN DEPARTMENT" && $department->name !== "EDUKASYON SA PAGPAPAHALAGA DEPARTMENT";
                        });
                        $selectedDepartment = $departmentsFullLoads->random();
                    }

                    //remove current selection from list
                    $departments = $departments->reject(function ($department) use ($selectedDepartment) {
                        return $department->id === $selectedDepartment->id;
                    });

                    //filter teacher for current Grade level and department for each period
                    $filteredTeachers = $this->getFilteredTeachers($gradelevel, $selectedDepartment, $advisory);

                    $randomTeacher = $filteredTeachers->random();
                    //Fill up the available slots for the current period
                    $classdays = [];
                    $selectedDepartmentID = $selectedDepartment->name;

                    $subjectName = $selectedDepartment->subjects()->first()->name;
                    $meetings = $selectedDepartment->subjects()->first()->hours_per_week;
                    $periodObj = new Period($subjectName);

                    //load assigning for Teacher
                    if ($advisory) {
                        $filteredTeachersAdvisory = $this->getFilteredTeachers($gradelevel, $selectedDepartment, $advisory);
                        do {
                            $randomTeacher = $filteredTeachersAdvisory->random();
                            $filteredTeachersAdvisory->forget($filteredTeachersAdvisory->search($randomTeacher));
                            // || !($this->loadCount($randomTeacher) < 5)
                        } while ($this->checkIfAdvisor($randomTeacher));
                        $this->assignRegularLoads($randomTeacher, "Advisory");
                        $advisory = false;
                    }
                    $this->assignRegularLoads($randomTeacher, $section->name);
                    //assign in claasday
                    while ($meetings > 0) {
                        $classdayArr = [];
                        for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                            if (!isset($reservedClassdays[$periodIndex-1][$classdayIndex])) {
                                $classday = $data->classdays->where('rank', $classdayIndex + 1)->first();
                                if (!in_array($classday, $classdayArr)) {
                                    $reservedClassdays[$periodIndex-1][$classdayIndex] = $subjectName;
                                    $classdayArr[] = $classday->short_name; //can change back to model
                                    $meetings--;
                                }
                            }
                            if ($meetings == 0) {
                                break;
                            }
                        }
                        if (!empty($classdayArr)) {
                            $classdays[] = $classdayArr;
                            $periodObj->setClass($classdayArr);
                            $periodObj->setTeacher($randomTeacher);
                        }
                    }

                    //if current period is not full, store it in array of period with vacant
                    if (!($this->checkPeriodFull($reservedClassdays, $periodIndex-1, $classdayCount))) {
                        $periodsWithVacantSlot[] = $periodIndex-1;
                    }

                    if ($unsetFlag) {
                        unset($reservedClassdays[$unsetPeriod][$unsetClassday]);
                    }

                    $class->addPeriod([$periodObj->toArray()]); //to add mul
                    $periodIndex++; //increment sa last part
                }

                // Insert last vacant subject
                $insertSubject = $reservedSubject->subjects()->first();
                $randomTeacher = ($this->getFilteredTeachers($gradelevel, $reservedSubject, $advisory))->random();
                $this->assignRegularLoads($randomTeacher, $section->name);
                $index = 0;
                $numOfMeetings = $insertSubject->hours_per_week;
                $periodObj = new Period($insertSubject->name);
                while ($numOfMeetings > 0) {
                    $classdayArr = [];
                    for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                        if (!isset($reservedClassdays[$periodsWithVacantSlot[$index]][$classdayIndex])) {
                            $classday = $data->classdays->where('rank', $classdayIndex + 1)->first();
                            if (!in_array($classday, $classdayArr)) {
                                $reservedClassdays[$periodsWithVacantSlot[$index]][$classdayIndex] = $insertSubject->name;
                                $classdayArr[] = $classday->short_name; //can change back to model
                                $numOfMeetings--;
                            }
                        }
                        if ($numOfMeetings == 0) {
                            break;
                        }
                    }
                    if (!empty($classdayArr)) {
                        $periodObj->setClass($classdayArr);
                        $periodObj->setTeacher($randomTeacher);
                    }
                    $class->insertPeriod($periodsWithVacantSlot[$index], $periodObj->toArray());
                    $index++;
                } // end of inserting

                $newChromosome[] = $class->toArray();
                // dd($class);
            }
        }
        $this->chromosome = $newChromosome;
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

    private function getFilteredTeachers($gradelevel, $selectedDepartment, $isAdvisory)
    {
        $teacher_loading = $this->teacher_loading;

        $filteredTeachers = $this->schoolprogram->teachers()
                    ->whereHas('gradelevel', function ($query) use ($gradelevel) {
                        $query->where('id', $gradelevel->id);
                    })->whereHas('department', function ($query) use ($selectedDepartment) {
                        $query->where('id', $selectedDepartment->id);
                    })->pluck('full_name');

        if ($isAdvisory) {
            $minCount = $filteredTeachers->reject(function ($teacher) use ($teacher_loading) {
                return in_array('Advisory', $teacher_loading[$teacher] ?? []);
            })->min(function ($teacher) use ($teacher_loading) {
                return count($teacher_loading[$teacher] ?? []);
            });

            return $filteredTeachers->reject(function ($teacher) use ($teacher_loading, $minCount) {
                return in_array('Advisory', $teacher_loading[$teacher] ?? []) || count($teacher_loading[$teacher] ?? []) > $minCount;
            })->values();
        }

        $minCount = $filteredTeachers->min(function ($teacher) use ($teacher_loading) {
            return count($teacher_loading[$teacher] ?? []);
        });

        return $filteredTeachers->filter(function ($teacher) use ($teacher_loading, $minCount) {
            return count($teacher_loading[$teacher] ?? []) == $minCount;
        });
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

    public function calculateFitness()
    {
        $this->conflicts = 0;
        $teacher_schedules = [];
        $conflicting_teachers = [];
        $teacher_section_counts = [];

        foreach ($this->chromosome as $schedule) {
            $section = $schedule['section']->name;
            foreach ($schedule['period'] as $periodIndex => $period) {
                foreach ($period as $classBlock) {
                    $teacher_id = $classBlock['teacher'];
                    $grade_level = $schedule['gradelevel']['level'];

                    if (!isset($teacher_schedules[$grade_level][$teacher_id])) {
                        $teacher_schedules[$grade_level][$teacher_id] = [];
                    }

                    if (!isset($teacher_schedules[$grade_level][$teacher_id][$periodIndex])) {
                        $teacher_schedules[$grade_level][$teacher_id][$periodIndex] = [];
                    }

                    // Check for conflicts
                    foreach ($classBlock['classday'] as $day) {
                        if (in_array($day, $teacher_schedules[$grade_level][$teacher_id][$periodIndex])) {
                            $conflicting_teachers[$grade_level][$day][$teacher_id][] = $section;
                            $this->conflicts++;
                        } else {
                            $teacher_schedules[$grade_level][$teacher_id][$periodIndex][] = $day;
                        }
                    }
                }
            }
        }
        $this->teacher_conflicts = $conflicting_teachers;
        return round((1 / ($this->conflicts + 1)), 6);
    }

    public function save()
    {
        //save schedule to database
    }
}
