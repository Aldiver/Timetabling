<?php

namespace App\Http\Services;

use App\Http\Services\ClassData;
use App\Http\Services\Subject;
use App\Models\Teacher;

class Schedule implements \Serializable
{
    private $schoolprogram;
    private $fitness;
    private $schedules;
    private $id;
    private $conflicts;
    private $fitnessChanged = true;
    private $teacher_loading = [];

    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
        $this->generateSchedule();
        // $this->calculateFitness();
    }

    public function generateSchedule()
    {
        $this->teacher_loading = [];
        //Assign admin task and OHSP randomly to teachers
        $admin = ['Admin1', 'Admin2', 'Admin3', 'Admin4', 'Admin5', 'Admin6', 'Admin7', 'Admin8', 'Admin9', 'Admin10'];
        // dd($this->schoolprogram->teachers);
        $this->assignOHSPLoads();
        $this->assignAdminLoads($this->schoolprogram->teachers, collect($admin));

        $classID = 0;
        $this->schedules = [];
        $classdayCount = $this->schoolprogram->classdays->count();
        $periodCount = $this->schoolprogram->periods->count();
        foreach ($this->schoolprogram->gradelevels as $gradelevel) {
            foreach ($this->schoolprogram->sections as $section) {
                $class = new ClassData($classID++, $gradelevel, $section);
                $reservedClassdays = $this->createScheduleBlocks($periodCount, $classdayCount);
                foreach ($this->schoolprogram->departments as $department) {
                    $filteredTeachers = $this->schoolprogram->teachers()->whereHas('gradelevel', function ($query) use ($gradelevel) {
                        $query->where('id', $gradelevel->id);
                    })->whereHas('department', function ($query) use ($department) {
                        $query->where('id', $department->id);
                    })->get();
                    $randomTeacher = $filteredTeachers->random();
                    $periods = [];
                    $periodsArr = [];
                    $classdays = [];
                    $subjectName = $department->subjects()->first()->name;
                    $meetings = $department->subjects()->first()->hours_per_week;
                    $subject = new Subject($department->subjects()->first()->name, $randomTeacher);

                    while ($meetings > 0) {
                        if ($meetings == 4) {
                            $periodIndex = mt_rand(1, $periodCount);
                            while (!$this->checkAvailability($reservedClassdays, $periodIndex, $classdayCount)) {
                                $periodIndex = mt_rand(1, $periodCount);
                            }
                        } else {
                            $periodIndex = mt_rand(1, $periodCount);
                            while ($this->checkPeriodFull($reservedClassdays, $periodIndex, $classdayCount)) {
                                $periodIndex = mt_rand(1, $periodCount);
                            }
                        }
                        $classdayArr = [];
                        for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                            if (!isset($reservedClassdays[$periodIndex -1][$classdayIndex])) {
                                $classday = $this->schoolprogram->classdays->where('rank', $classdayIndex + 1)->first();
                                if (!in_array($classday, $classdayArr)) {
                                    $reservedClassdays[$periodIndex-1][$classdayIndex] = $subjectName;
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
                            $periods[] = $this->schoolprogram->periods()->where('rank', $periodIndex)->first();
                            $subject->setClass($periods, $classdays);
                        }
                    }
                    $class->addSubject($subject->toArray());
                }
                $this->schedules[] = $class->toArray();
            }
        }
        // dd($this->schedules);
        $this->getFitness();
        return $this->schedules;
    }

    public function getSchedules()
    {
        return $this->schedules;
    }

    //serialize the object to be readable in frontend
    public function toArray()
    {
        return [
            'schoolprogram' => $this->schoolprogram,
            'fitness' => $this->fitness,
            'schedules' => $this->schedules,
            'id' => $this->id,
            'teacher_loading' => $this->teacher_loading,
            'conflicts' => $this->conflicts,
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
        $this->teacher_loading = $data['teacher_loading'];
        $this->conflicts = $data['conflicts'];
    }

    public function __sleep()
    {
        return ['schoolprogram', 'classes', 'fitness', 'schedules', 'id', 'teacher_loading', 'conflicts'];
    }

    public function __wakeup()
    {
        // ...
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
            if (!isset($scheduleBlock[($period-1)][$i])) {
                $count++;
            }
            if ($count == 4) {
                return true;
            }
        }
        return false;
    }

    private function checkPeriodFull($scheduleBlock, $period, $classdayCount)
    {
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period-1)][$i])) {
                return false;
            }
        }
        return true;
    }

    private function getNumbOfConflicts()
    {
        return $this->conflicts;
    }

    public function getFitness()
    {
        if ($this->fitnessChanged) {
            $this->fitness = $this->calculateFitness();
            $this->fitnessChanged = false;
        }
        return $this->fitness;
    }

    private function calculateFitness()
    {
        $this->conflicts = 0;
        $teacher_schedules = [];
        $teacher_section_counts = [];

        foreach ($this->schedules as $schedule) {
            foreach ($schedule['subject'] as $subject) {
                $teacher = $subject['teacher'];
                $teacher_id = $teacher['id'];
                $grade_level = $schedule['gradelevel']['level'];

                if (!isset($teacher_schedules[$grade_level][$teacher_id])) {
                    $teacher_schedules[$grade_level][$teacher_id] = [];
                }

                foreach ($subject['class'] as $class) {
                    $period = $class['period']['rank'];
                    $classdays = array_map(function ($classday) {
                        return $classday['rank'];
                    }, $class['classday']);

                    if (!isset($teacher_schedules[$grade_level][$teacher_id][$period])) {
                        $teacher_schedules[$grade_level][$teacher_id][$period] = [];
                    }

                    foreach ($classdays as $classday) {
                        if (in_array($classday, $teacher_schedules[$grade_level][$teacher_id][$period])) {
                            $this->conflicts++;
                        } else {
                            $teacher_schedules[$grade_level][$teacher_id][$period][] = $classday;
                        }
                    }

                    // increment section count for the teacher handling this class
                    if (!isset($teacher_section_counts[$teacher_id])) {
                        $teacher_section_counts[$teacher_id] = 1;
                    } else {
                        $teacher_section_counts[$teacher_id]++;
                    }
                }
            }
        }
        return round((1 / ($this->conflicts + 1)), 6);
    }

    public function save()
    {
        //save schedule to database
    }
}
