<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Period;
use App\Models\Department;
use App\Models\Gradelevel;
use App\Models\SchoolProgram;
use App\Models\Timetable;

use App\Jobs\GenerateTimetableJob;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;
use App\Http\Services\TeacherLoading\AssignLoads;

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
        // $demo = Timetable::all();
        // foreach ($demo as $t) {
        //     $test = new AssignLoads($t);
        //     $test->run();
        // }

        $teachers = Teacher::count();
        $sections = Section::count();
        $departments = Department::count();
        $schoolProgram = SchoolProgram::All()->pluck('school_year', 'id');
        $timetables = Timetable::with('schoolprograms')->get();

        return Inertia::render('Data/Dashboard/Index', [
            'teachers' => $teachers, 'sections' => $sections, 'departments' => $departments,'schoolprogram' => $schoolProgram,
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
            'name' => $request->name,
            'current_level' => '',
        ]);

        $timetable->schoolprograms()->sync($schoolProgram);

        // $timetableGA = new TimetableGA($timetable);
        // $schedule = $timetableGA->run();

        dispatch(new GenerateTimetableJob($timetable));
    }

    public function destroy($id)
    {
        $timetable = Timetable::find($id);
        $jobId = $timetable->jobId;
        $timetable->delete();
        if($jobId) {
            // Find the PID of the queue worker process that is running the job
            $pid = `ps aux | grep 'php /var/www/html/Timetabling/artisan queue:work' | grep -v grep | awk '{print $2}'`;
            // $pidCommand = "ps aux | grep '{$processName}' | awk '{print $2}'";

            // Kill the queue worker process
            $killCommand = "kill -9 {$pid}";
            exec($killCommand);
        }



        return redirect()->route('dashboard.index')
                        ->with('message', __('Timetable deleted successfully'));
    }




    public function show($id, $table)
    {
        $timetable = Timetable::find($id);
        $sections = Section::All();
        $periods = Period::with('timeslot')->get();
        $schedules = ($table == 1) ? json_decode($timetable->schedule_data1) : json_decode($timetable->schedule_data2);
        return Inertia::render('Data/Dashboard/Show', [
            'timetable' => $timetable,
            'scheme' => $schedules,
            'sectionModel' => $sections,
            'periodModel' => $periods
        ]);
    }
}
