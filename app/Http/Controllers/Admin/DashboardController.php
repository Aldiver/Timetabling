<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Department;
use App\Models\Gradelevel;
use App\Models\SchoolProgram;
use App\Models\Timetable;

use App\Jobs\GenerateTimetableJob;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class DashboardController extends Controller
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
        $teachers = Teacher::count();
        $sections = Section::count();
        $departments = Department::count();
        $schedule = session('schedule');
        $schoolProgram = SchoolProgram::All()->pluck('school_year', 'id');
        $timetables = Timetable::All();

        return Inertia::render('Data/Dashboard/Index', [
            'teachers' => $teachers, 'sections' => $sections, 'departments' => $departments, 'schedule' => $schedule, 'schoolprogram' => $schoolProgram,
            'timetables' => $timetables,
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:timetables',
            'schoolProgram' => ['required'],
        ]);

        $schoolProgram = SchoolProgram::where('school_year', $request->schoolProgram)->first();
        $timetable = Timetable::create([
            // 'user_id' => Auth::user()->id,
            'status' => 'IN PROGRESS',
            'name' => $request->name
        ]);

        $timetable->schoolprograms()->sync($schoolProgram);

        // $selectedSchoolProgram = SchoolProgram::with(['gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods'])->find($schoolProgram->id);
        // $schedule = new GeneticAlgorithm2($selectedSchoolProgram);

        // $timetableGA = new TimetableGA($timetable);
        // $schedule = $timetableGA->run();

        dispatch(new GenerateTimetableJob($timetable));
    }

    public function destroy($id)
    {
        $timetable = Timetable::find($id);
        $timetable->delete();

        return redirect()->route('dashboard.index')
                        ->with('message', __('Timetable deleted successfully'));
    }

    public function show($id)
    {
        $timetable = Timetable::find($id);
        $schedules = json_decode($timetable->schedule_data1);
        return Inertia::render('Data/Dashboard/Show', [
            'timetable' => $timetable,
            'scheme' => $schedules
        ]);
    }
}
