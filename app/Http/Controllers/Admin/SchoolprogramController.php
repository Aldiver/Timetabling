<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schoolprogram;
use App\Models\Gradelevel;
use App\Models\Section;
use App\Models\Timeslot;
use App\Models\Classday;
use App\Models\Teacher;
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
        return Inertia::render('Data/Schoolprogram/Index', [
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
        return Inertia::render('Data/Schoolprogram/Create', [
            'gradelevels' => Gradelevel::all()->pluck("level","id"),
            'sections' => Section::all()->map->only('name', 'id', 'gradelevel_id'),
            'timeslots' => Timeslot::orderBy('rank', 'asc')->get(),
            'classdays' => Classday::orderBy('rank', 'asc')->get(),
            'teachers' => Teacher::all()->map->only('first_name', 'last_name', 'middle_name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schoolprogram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function show(Schoolprogram $schoolprogram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schoolprogram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function edit(Schoolprogram $schoolprogram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schoolprogram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schoolprogram $schoolprogram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schoolprogram  $schoolprogram
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schoolprogram $schoolprogram)
    {
        //
    }
}
