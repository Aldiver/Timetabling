<?php

namespace App\Http\Services;

use App\Http\Services\ClassData;
use App\Http\Services\Subject;
use App\Models\Teacher;

class Schedule implements \Serializable
{
    private $schoolprogram;
    private $classes;
    private $fitness;
    private $schedules;
    private $id;

    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
        $this->generateSchedule();
        // $this->calculateFitness();
    }

    public function generateSchedule()
    {
        $this->id = 0;
        $this->schedules = [];
        $classdayCount = $this->schoolprogram->classdays->count();
        $periodCount = $this->schoolprogram->periods->count();
        foreach ($this->schoolprogram->gradelevels as $gradelevel) {
            foreach ($this->schoolprogram->sections as $section) {
                $class = new ClassData($this->id++, $gradelevel, $section);
                $reservedClassdays = $this->createScheduleBlocks($periodCount, $classdayCount);
                foreach ($this->schoolprogram->departments as $department) {
                    $teachers = $this->schoolprogram->teachers;
                    $filteredTeachers = $teachers->filter(function ($teacher) use ($gradelevel, $department) {
                        return $teacher->gradelevel->contains($gradelevel) && $teacher->department->contains($department);
                    });
                    $randomTeacher = $filteredTeachers->random();
                    $periods = [];
                    $classdays = [];
                    $meetings = $department->subjects()->first()->hours_per_week;
                    $subject = new Subject($department->subjects()->first()->name, $randomTeacher);

                    while ($meetings > 0) {
                        $periodIndex = mt_rand(1, $periodCount);
                        $periods[] = $this->schoolprogram->periods()->where('rank', $periodIndex)->first();
                        $classdayArr = [];
                        for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                            if (!isset($reservedClassdays[$periodIndex -1][$classdayIndex])) {
                                $classday = $this->schoolprogram->classdays->where('rank', $classdayIndex + 1)->first();
                                if (!in_array($classday, $classdayArr)) {
                                    $reservedClassdays[$periodIndex-1][$classdayIndex] = true;
                                    $classdayArr[] = $classday;
                                    $meetings--;
                                }
                            }
                            if ($meetings == 0) {
                                break;
                            }
                        }
                        if (!empty($classdayArr)) {
                            $classdays[] = $classdayArr;
                            $subject->setClass($periods, $classdays);
                        }
                    }
                    $class->addSubject($subject->toArray());
                }
                $this->schedules[] = $class->toArray();
            }
        }
        // return $this->schedules;
    }

    public function getSchedules()
    {
        return $this->schedules;
    }

    public function toArray()
    {
        return [
            'schoolprogram' => $this->schoolprogram,
            'classes' => $this->classes,
            'fitness' => $this->fitness,
            'schedules' => $this->schedules,
            'id' => $this->id,
        ];
    }

    public function serialize()
    {
        return serialize($this->toArray());
    }

    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->schoolprogram = $data['schoolprogram'];
        $this->classes = $data['classes'];
        $this->fitness = $data['fitness'];
        $this->schedules = $data['schedules'];
        $this->id = $data['id'];
    }

    public function __sleep()
    {
        return ['schoolprogram', 'classes', 'fitness', 'schedules', 'id'];
    }

    public function __wakeup()
    {
        // ...
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

    private function isPeriodFull($scheduleBlock, $period, $classdayCount)
    {
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period-1)][$i])) {
                return false;
            }
        }
        return true;
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
