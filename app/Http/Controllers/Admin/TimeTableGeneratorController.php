<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Services\Schedule;

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

    public function generateTimetable()
    {
        $selectedSchoolProgram = SchoolProgram::with(['gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods'])->find(2);
        // dd($selectedSchoolProgram);
        $schedule = new Schedule($selectedSchoolProgram);

        // dd($schedule);
        return Inertia::render('Data/Dashboard/Index', [
            'schedule' => $schedule->toArray(),
        ]);
    }
}
