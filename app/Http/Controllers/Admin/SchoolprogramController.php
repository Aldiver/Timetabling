<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolProgram;
use App\Models\Gradelevel;
use App\Models\Section;
use App\Models\Timeslot;
use App\Models\Classday;
use App\Models\Teacher;
use App\Models\Period;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SchoolprogramController extends Controller
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
        $schoolprogram = SchoolProgram::with('gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods')->get();


        return Inertia::render('Data/Schoolprogram/Index', [
            'schoolprogram' => $schoolprogram,
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = Teacher::with('gradelevel')->get()->groupBy(function ($teacher) {
            return $teacher->gradelevel()->first()->id;
        });

        return Inertia::render('Data/Schoolprogram/Create', [
            'gradelevels' => Gradelevel::all()->pluck("level", "id"),
            'sections' => Section::all()->map->only('name', 'id', 'gradelevel_id'),
            'periods' => Period::orderBy('rank', 'asc')->get(),
            'timeslots' => Timeslot::all()->pluck("time", "id"),
            'classdays' => Classday::orderBy('rank', 'asc')->get(),
            'teachers' => $teachers,
        ]);
    }

    /**
     * checkForm a checks the form inputs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkForm(Request $request)
    {
        $stepvalue = $request->input('stepValue');


        switch ($stepvalue) {
            case "SCHOOLYEAR":
                $request->validate([
                    'school_year' => ['required'],
                ]);
                break;
            case "GRADELEVEL":
                $request->validate([
                    'levels' => 'required|array',
                ]);
                break;
            case 'SECTION':
                $request->validate([
                    'sections' => 'required|array',
                ]);
                break;
            case 'SCHEDULE':
                $request->validate([
                    'periods' => 'required|array',
                    'classdays' => 'required|array',
                ]);
                break;
            default:
                // code to be executed if $expression is different from all cases;
                break;
        }

        return to_route('schoolprogram.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $departments = Department::all();
        $request->validate([
            'teachers' => ['required'],
        ]);

        // dd($request->all());

        $schoolProgram = SchoolProgram::create([
            'school_year' => $request->school_year,
        ]);

        $schoolProgram->gradelevels()->attach($request->levels);

        foreach ($request->classdays as $classday) {
            $schoolProgram->classdays()->attach($classday['id']);
        };
        foreach ($request->sections as $section) {
            $schoolProgram->sections()->attach($section['id']);
        };
        foreach ($request->teachers as $teacher) {
            $schoolProgram->teachers()->attach($teacher['id']);
        };
        foreach ($request->periods as $period) {
            $schoolProgram->periods()->attach($period['id']);
        };

        $schoolProgram->departments()->sync($departments);

        return redirect()->route('schoolprogram.index')
                        ->with('message', __('school program added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolProgram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolProgram $schoolprogram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchoolProgram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolProgram $schoolprogram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolProgram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolProgram $schoolprogram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolProgram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolProgram $schoolprogram)
    {
        $schoolprogram->delete();

        return redirect()->route('schoolprogram.index')
                        ->with('message', __('School Program deleted successfully'));
    }
}
