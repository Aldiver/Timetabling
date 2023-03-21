<?php

namespace App\Http\Services\TeacherLoading;

use App\Models\Timetable as TimetableModel;
use App\Models\Teacher;

class AssignLoads
{
    /**
     * Timetable we want to run the algorithm for
     *
     * @var App\Models\Timetable
     */
    protected $timetable;

    /**
     * Create a new instance of TimetableGA class
     *
     * @param App\Models\Timetable $timetable Timetable we want to run the algorithm
     *                                        to generate
     */
    public function __construct(TimetableModel $timetable) //TimetableModel $timetable
    {
        $this->timetable = $timetable;
    }

    public function run()
    {
        $data1 = json_decode($this->timetable->schedule_data1, true);
        $data2 = json_decode($this->timetable->schedule_data2, true);

        $teacher_loading = [];
        foreach ($data1 as $timetable) {
            foreach ($timetable as $gradelevel) {
                foreach ($gradelevel as $section) {
                    foreach ($section as $class) {
                        $teacher = Teacher::where('full_name', $class[0])->first();
                        if (!isset($teacher_loading[$teacher->id])) {
                            $teacher_loading[$teacher->id] = [];
                        } else {
                            if ($class[2] == "D2T1") {
                                $teacher_loading[$teacher->id]['Advisory'] = key($gradelevel);
                            } else {
                                $teacher_loading[$teacher->id]['Sections'][] = key($gradelevel);
                            }
                        }
                    }

                    dd($teacher_loading);
                }
            }
        }
    }
}
