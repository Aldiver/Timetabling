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
    private $teacher_conflicts;
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
        //Initialize constants for each class schedules
        $this->schedules = [];
        $this->teacher_loading = [];
        $classID = 0;
        $teachersId =  $this->schoolprogram->teachers()->pluck('id');
        $gradelevel = $this->schoolprogram->with('gradelevels.sections')->first();
        $classdayCount = $this->schoolprogram->classdays->count();
        $periodCount = $this->schoolprogram->periods->count();
        $periods = $this->schoolprogram->periods()->with('timeslot')->get();
        //Assign admin task and OHSP randomly to teachers'
        //$admin = $this->schoolprogram->admins;
        $admin = ['Admin1', 'Admin2', 'Admin3', 'Admin4', 'Admin5', 'Admin6', 'Admin7', 'Admin8', 'Admin9', 'Admin10'];

        // $this->assignOHSPLoads();
        // $this->assignAdminLoads($this->schoolprogram->teachers, collect($admin));

        foreach ($this->schoolprogram->gradelevels as $gradelevel) {
            //unique per grade level
            $departmentLastIndex = 1;
            foreach ($gradelevel->sections as $section) {
                //create a class schedule for each section
                $class = new ClassData($classID++, $gradelevel, $section);
                $reservedClassdays = $this->createScheduleBlocks($periodCount, $classdayCount);
                $departments = $this->schoolprogram->departments;
                $periodIndex = 1;


                //section unique variables

                $periodsWithVacantSlot = [];
                $advisory = false;
                foreach ($periods as $period) {
                    if ($periodIndex == 1) {
                        $advisory = true;
                        if ($departmentLastIndex > 8) {
                            $departmentLastIndex = 1;
                        }
                        $selectedDepartment = $departments->find($departmentLastIndex);
                        $departmentLastIndex++;
                    } else {
                        $selectedDepartment = $departments->random();
                    }

                    //remove current selection from list
                    $departments = $departments->reject(function ($department) use ($selectedDepartment) {
                        return $department->id === $selectedDepartment->id;
                    });

                    //filter teacher for current Grade level and department for each period
                    $filteredTeachers = $this->getFilteredTeachers($gradelevel, $selectedDepartment);

                    //Fill up the available slots for the current period
                    $classdays = [];
                    $selectedDepartmentID = $selectedDepartment->id;
                    $subjectName = $selectedDepartment->subjects()->first()->name;
                    $meetings = $selectedDepartment->subjects()->first()->hours_per_week;
                    $periodObj = new Period($subjectName);
                    $randomTeacher = $filteredTeachers->random();

                    //load assigning for Teacher
                    if ($advisory) {
                        do {
                            $randomTeacher = $filteredTeachers->random();
                            $filteredTeachers->forget($filteredTeachers->search($randomTeacher));
                        } while ($this->checkIfAdvisor($randomTeacher) || !($this->loadCount($randomTeacher) < 5));
                        $this->assignRegularLoads($randomTeacher, "Advisory");
                        $advisory = false;
                    } else {
                        do {
                            $randomTeacher = $filteredTeachers->random();
                            $filteredTeachers->forget($filteredTeachers->search($randomTeacher));
                        } while (!($this->loadCount($randomTeacher) < 8) && ($selectedDepartmentID != 8));
                    }
                    $this->assignRegularLoads($randomTeacher, $section->name);

                    while ($meetings > 0) {
                        $classdayArr = [];
                        for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                            if (!isset($reservedClassdays[$periodIndex-1][$classdayIndex])) {
                                $classday = $this->schoolprogram->classdays->where('rank', $classdayIndex + 1)->first();
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
                    //if last subject is either TLE or ESP, remove the other one from the list of selections
                    if (($selectedDepartmentID == 7 || $selectedDepartmentID == 8)) {
                        // && !($departments->count() < 1)
                        $reservedSubject = ($selectedDepartmentID == 7) ? 8 : 7;
                        $departments = $departments->reject(function ($department) use ($reservedSubject) {
                            return $department->id === $reservedSubject;
                        });
                        $reservedSubject = $this->schoolprogram->departments()->find($reservedSubject);
                        // dd($selectedDepartmentID, $selectedDepartment->name, $reservedSubject->id, $reservedSubject->name);
                    }

                    //if current period is not full, store it in array of period with vacant
                    if (!($this->checkPeriodFull($reservedClassdays, $periodIndex-1, $classdayCount))) {
                        $periodsWithVacantSlot[] = $periodIndex-1;
                    }

                    $class->addPeriod([$periodObj->toArray()]); //to add mul
                    $periodIndex++; //increment sa last part
                }

                //Insert last vacant subject
                $insertSubject = $reservedSubject->subjects()->first();
                $randomTeacher = ($this->getFilteredTeachers($gradelevel, $reservedSubject))->random();
                $index = 0;
                $numOfMeetings = $insertSubject->hours_per_week;
                $periodObj = new Period($insertSubject->name);
                while ($numOfMeetings > 0) {
                    $classdayArr = [];
                    if ($index==2) {
                        dd($periodsWithVacantSlot, $index, $reservedClassdays);
                    }
                    for ($classdayIndex = 0; $classdayIndex < 5; $classdayIndex++) {
                        if (!isset($reservedClassdays[$periodsWithVacantSlot[$index]][$classdayIndex])) {
                            $classday = $this->schoolprogram->classdays->where('rank', $classdayIndex + 1)->first();
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

                $this->schedules[] = $class->toArray();
                // dd($class);
            }
        }
        // dd(($this->teacher_loading));
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
            'teacher_conflicts' => $this->teacher_conflicts,
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
        $this->teacher_conflicts = $data['teacher_conflicts'];
    }

    public function __sleep()
    {
        return ['schoolprogram', 'classes', 'fitness', 'schedules', 'id', 'teacher_loading', 'conflicts', 'teacher_conflicts'];
    }

    public function __wakeup()
    {
        // ...
    }

    private function getFilteredTeachers($gradelevel, $selectedDepartment)
    {
        return $this->schoolprogram->teachers()
                    ->whereHas('gradelevel', function ($query) use ($gradelevel) {
                        $query->where('id', $gradelevel->id);
                    })->whereHas('department', function ($query) use ($selectedDepartment) {
                        $query->where('id', $selectedDepartment->id);
                    })->pluck('full_name');
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
            if ($count == 4) {
                return true;
            }
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
        $conflicting_teachers = [];
        $teacher_section_counts = [];

        foreach ($this->schedules as $schedule) {
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
        // dd($this->conflicts, $conflicting_teachers);
        // dd($teacher_schedules, $this->teacher_loading);
        return round((1 / ($this->conflicts + 1)), 6);
    }

    public function save()
    {
        //save schedule to database
    }
}
