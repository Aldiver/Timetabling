<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolProgram;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Services\Population;
use App\Http\Services\GeneticAlgorithm;
use App\Http\Services\Schedule;
use App\Jobs\GenerateTimetableJob;
use Illuminate\Support\Facades\Redirect;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class TimeTableGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:permission list', ['only' => ['index', 'show']]);
        $this->middleware('can:permission create', ['only' => ['create', 'store']]);
        $this->middleware('can:permission edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:permission delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Insert Program Here
    }

    public function generateTimetable($request)
    {
        // $selectedSchoolProgram = SchoolProgram::with(['gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods'])->find(1);

        // $timetableGA = new TimetableGA(1);
        // $schedule = $timetableGA->run();

        $schedule = dispatch(new GenerateTimetableJob(1));
    }
}
