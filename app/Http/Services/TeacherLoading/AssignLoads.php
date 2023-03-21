<?php

namespace App\Http\Services\TeacherLoading;

use App\Models\Timetable as TimetableModel;
use App\Models\Teacher;
use App\Models\TeacherLoading;

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

        // $teachingLoad1 = new TeachingLoad
        foreach ($data1 as $timetable) {
            foreach ($timetable as $gradelevel) {
                // dd($gradelevel);
                foreach ($gradelevel as $section) {
                    // dd(key($gradelevel), $section);
                }
            }
        }
        // dd($data1);
        // $teachingLoad1 = TeacherLoading::create([
        //     'version' => 1,
        // ]);
        // $teachingLoad->timetable()->associate($this->timetable);
        // $teachingLoad->save();
    }
}
