<?php

namespace App\Http\Services\TeacherLoading;

use App\Models\Timetable as TimetableModel;
use App\Models\Teacher;
use App\Models\TeacherLoading;
use App\Models\Admin;
use App\Models\Section;

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
        foreach ($data2 as $gradelevel) {
            foreach ($gradelevel as $section) {
                foreach ($section as $key => $classes) {
                    $section_model = Section::find($key);

                    foreach ($classes as $class) {
                        $teacher = Teacher::where('full_name', $class[0])->first();
                        if (!isset($teacher_loading[$teacher->id])) {
                            // $teacher_loading[$teacher->id] = [];
                            $teacher_loading[$teacher->id]['Advisory'] = null;
                            $teacher_loading[$teacher->id]['Sections'] = [];
                            $teacher_loading[$teacher->id]['Admin'] = null;
                        }

                        if ($class[2] == "D2T1") {
                            $teacher_loading[$teacher->id]['Advisory'] = $section_model->name;
                            array_push($teacher_loading[$teacher->id]['Sections'], $section_model->name);
                        } elseif (strpos($class[2], "D5") !== false) {
                            // $teacher_loading[$teacher->id]['Ohsp'] = "BLOCKED";
                        } else {
                            // dd($teacher_loading[$teacher->id], $individual, key($sections));
                            if (!in_array($section_model->name, $teacher_loading[$teacher->id]['Sections'])) {
                                array_push($teacher_loading[$teacher->id]['Sections'], $section_model->name);
                            }
                        }
                    }
                }
            }
        }
        $adminLoads = Admin::all()->shuffle();

        // dd($adminLoads);
        foreach ($adminLoads as $admin) {
            $random = array_rand($teacher_loading);
            if (!isset($teacher_loading[$random]['Admin'])) {
                $teacher_loading[$random]['Admin'] = $admin->name;
            }
        }
        // dd("okay", $teacher_loading);

        // $this->timetable->oshp->first();

        foreach ($teacher_loading as $key => $teacher) {
            // dd($teacher, $teacher['Advisory']);
            $teacherLoading = TeacherLoading::create([
                'teacher_id' => $key,
                'timetable_id' => $this->timetable->id,
                'version' => 1,
                'load' => json_encode($teacher),
                'teacher_name' => Teacher::find($key)->full_name
            ]);
        }
        // dd("okay", $teacher_loading);
    }
}
