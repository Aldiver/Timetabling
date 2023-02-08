<?php

use App\Http\Services\ClassData;

class Schedule
{
    private $classes;
    private $fitness;
    private $schedules;
    private $id = 0;

    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
        // $this->generateSchedule();
        // $this->calculateFitness();
    }

    public function generateSchedule()
    {
        foreach ($this->$schoolprogram->gradelevels as $gradelevel) {
            foreach ($this->$schoolprogram->sections as $section) {
                $class = new ClassData($this->id++, $gradelevel, $section);
                $reservedClassdays = $this->createScheduleBlocks($this->schoolprogram->periods->count, $this->schoolprogram->classdays->count);
                foreach ($this->$schoolprogram->departments as $department) {
                    $teacher = Teacher::getRandomTeacher($this->$schoolprogram, $gradelevel, $department);
                    $periods = [];
                    $classdays = [];
                    $meetings = $department->subject->number_of_meeting;
                    while ($meetings > 0) {
                        $periodIndex = mt_rand(0, $schoolprogram->periods->count() - 1);
                        $periods[] = $schoolprogram->period->find($periodIndex);
                        $classdayArr = [];
                        for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                            if (!isset($reservedClassdays[$periodIndex][$classdayIndex])) {
                                $classday = $schoolprogram->classdays->where('rank', $classdayIndex + 1)->first();
                                if (!in_array($classday, $classdays)) {
                                    $classdayArr[] = $classday;
                                    $meetings--;
                                }
                            }
                        }
                        $classdays[] = $classdayArr;
                    }
                    $subject = new Subject($department->subject->name, $periods, $classdays, $teacher);
                    $class->addSubject($subject);
                }
            }
        }
    }

    private function createScheduleBlocks($periodCount, $classdayCount)
    {
        $reservedClassdays = [];

        $reservedClassdays[0][0] = true;  // Reserve first classday of first period
        $reservedClassdays[$periodCount-1][$classdayCount-1] = true;  // Reserve last classday of last period

        // Reserve random classdays between first and last periods
        for ($i = 0; $i < 4; $i++) {
            $randomPeriodIndex = mt_rand(1, $periodCount - 2);
            $randomClassdayIndex = mt_rand(0, $classdayCount - 1);

            if (!isset($reservedClassdays[$randomPeriodIndex][$randomClassdayIndex])) {
                $reservedClassdays[$randomPeriodIndex][$randomClassdayIndex] = true;
            } else {
                // If the block is already reserved, decrement $i and try again
                $i--;
            }
        }

        return $reservedClassdays;
    }


    private function calculateFitness()
    {
        $fitness = 0;
        //calculate fitness
        //apply the conditions and constraints
        //- check for conflicts in schedule
        for ($i = 0; $i < $this->numOfClasses; $i++) {
            for ($j = 5; $j <= 8; $j++) {
                if (isset($this->classes[$i][$j])) {
                    $subject = $this->classes[$i][$j];
                    for ($k = 0; $k < $this->numOfClasses; $k++) {
                        if ($k != $i) {
                            if (isset($this->classes[$k][$j]) && $this->classes[$k][$j]->name == $subject->name) {
                                $fitness--;
                            }
                        }
                    }
                }
            }
        }

        //- check if all subjects in a section have teachers
        for ($i = 0; $i < $this->numOfClasses; $i++) {
            for ($j = 5; $j <= 8; $j++) {
                if (isset($this->classes[$i][$j]) && !isset($this->classes[$i][$j]->teacher)) {
                    $fitness--;
                }
            }
        }

        //- check if all admin task are assigned once to a random teacher
        $admin_task_count = array();
        for ($i = 0; $i < $this->numOfClasses; $i++) {
            for ($j = 5; $j <= 8; $j++) {
                if (isset($this->classes[$i][$j]) && $this->classes[$i][$j]->type == "admin") {
                    if (!isset($admin_task_count[$this->classes[$i][$j]->name])) {
                        $admin_task_count[$this->classes[$i][$j]->name] = 0;
                    }
                    $admin_task_count[$this->classes[$i][$j]->name]++;
                    if ($admin_task_count[$this->classes[$i][$j]->name] > 1) {
                        $fitness--;
                    }
                }
            }
        }

        //- check if all special task are assigned
        //- check if special task is not assigned to a teacher with subject at the first time slot
        //- check if special task is not assigned to a teacher with more than 2 special task
        $special_task_count = array();
        for ($i = 0; $i < $this->numOfClasses; $i++) {
            for ($j = 5; $j <= 8; $j++) {
                if (isset($this->classes[$i][$j]) && $this->classes[$i][$j]->type == "special") {
                    if (!isset($this->classes[$i][$j]->teacher)) {
                        $fitness--;
                    } else {
                        if (!isset($special_task_count[$this->classes[$i][$j]->teacher])) {
                            $special_task_count[$this->classes[$i][$j]->teacher] = 0;
                        }
                        if (isset($this->classes[$i][1]) && $this->classes[$i][1]->teacher == $this->classes[$i][$j]->teacher) {
                            $fitness--;
                        } elseif ($special_task_count[$this->classes[$i][$j]->teacher] >= 2) {
                            $fitness--;
                        } else {
                            $special_task_count[$this->classes[$i][$j]->teacher]++;
                        }
                    }
                }
            }
        }

        $this->fitness = $fitness;
    }

    public function getFitness()
    {
        return $this->fitness;
    }

    public function save()
    {
        //save schedule to database
    }
}
