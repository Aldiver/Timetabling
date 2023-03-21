<?php

namespace App\Http\Services\TeacherLoading;

use App\Models\Timetable as TimetableModel;

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
    }
}
