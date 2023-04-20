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
        $timetableData[] = json_decode($this->timetable->schedule_data1, true);
        $timetableData[] = json_decode($this->timetable->schedule_data2, true);
        $version = 1;

        foreach ($timetableData as $data) {
            $teacher_loading = [];
            foreach ($data as $gradelevel) {
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
                                if (!in_array($section_model->name, $teacher_loading[$teacher->id]['Sections'])) {
                                    array_push($teacher_loading[$teacher->id]['Sections'], $section_model->name);
                                }
                            }
                        }
                    }
                }
            }
            $adminLoads = Admin::all()->shuffle();
            uksort($teacher_loading, function () {
                return rand() > rand();
            });
            // dd($teacher_loading);
            foreach ($adminLoads as $admin) {
                $random = array_rand($teacher_loading);
                while (isset($teacher_loading[$random]['Admin'])) {
                    // dd($teacher_loading[$random]['Admin']);
                    $random = array_rand($teacher_loading);
                }
                $teacher_loading[$random]['Admin'] = $admin->name;
            }

            foreach ($teacher_loading as $key => $teacher) {
                $teacherLoading = TeacherLoading::create([
                    'teacher_id' => $key,
                    'timetable_id' => $this->timetable->id,
                    'version' => $version,
                    'load' => json_encode($teacher),
                    'teacher_name' => Teacher::find($key)->full_name
                ]);
            }

            $version++;
        }
    }

    public function clearLoads()
    {
        $teacherLoads = TeacherLoading::where('timetable_id', $this->timetable->id)->get();
        foreach ($teacherLoads as $teacher) {
            $load = json_decode($teacher->load, true);
            $load['Admin'] = null;
            $teacher->update([ 'load' => json_encode($load)]);
        }

        return $teacherLoads;
    }
}
