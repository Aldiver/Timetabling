<?php

namespace App\Http\Services\TeacherLoading;

use App\Models\Timetable as TimetableModel;
use App\Models\Teacher;
use App\Models\TeacherLoading;
use App\Models\Admin;

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
        $secIds = [];
        $teacher_loading = [];
        foreach ($data1 as $gradelevel) {
            foreach ($gradelevel as $section) {
                foreach ($section as $key => $classes) {
                    foreach ($classes as $class) {
                        $teacher = Teacher::where('full_name', $class[0])->first();
                        if (!isset($teacher_loading[$teacher->id])) {
                            // $teacher_loading[$teacher->id] = [];
                            $teacher_loading[$teacher->id]['Advisory'] = null;
                            $teacher_loading[$teacher->id]['Sections'] = [];
                        }

                        if ($class[2] == "D2T1") {
                            $teacher_loading[$teacher->id]['Advisory'] = $key;
                            array_push($teacher_loading[$teacher->id]['Sections'], $key);
                        } else {
                            // dd($teacher_loading[$teacher->id], $individual, key($sections));
                            if (!in_array($key, $teacher_loading[$teacher->id]['Sections'])) {
                                array_push($teacher_loading[$teacher->id]['Sections'], $key);
                            }
                        }
                    }
                }
            }
        }
        // dd($teacher_loading);
        $adminLoads = Admin::all();

        // dd($adminLoads);
        foreach ($teacher_loading as $key => $teacher) {
            //
        }

        $teacherLoading = TeacherLoading::create([
            'teacher_id' => $teacherId,
            'timetable_id' => $this->timetable->id,
            'version' => $version,
            'load' => [
                'Admin' => 'Value',
                'Sections' => [$value],
                'Advisory' => 'Value',
                'SpecialLoad' => 123
            ]
        ]);
    }
}
