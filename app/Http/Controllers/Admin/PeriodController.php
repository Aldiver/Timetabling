<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Timeslot;
use App\Models\Classday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PeriodController extends Controller
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
        $periods = (new Period)->newQuery();
        $relatedTimeslots = Timeslot::all()->pluck("time","id");
        $classdays = Classday::all()->pluck("short_name","id");
        $unassignedTimeslots = Timeslot::doesntHave('period')->get();


        $periods = $periods->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Period/Index', [
            'periods' => $periods,
            'timeslots' => $relatedTimeslots,
            'unassignedTimeslots' => $unassignedTimeslots,
            'classdays' => $classdays,
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
        $unassignedTimeslots = Timeslot::doesntHave('period')->get();
        return Inertia::render('Data/Period/Create', ['timeslots' => $unassignedTimeslots,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StorePermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'rank' => 'required',
            'timeslot' => 'required',
        ]);
        $timeslot = Timeslot::where('time', $request->timeslot)->first();
        $period = Period::create([
            'rank'=> $request->rank,
        ]);
        $timeslot->period()->save($period);

        return redirect()->route('period.index')
                        ->with('message', __('Period added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Period $period)
    {
        return Inertia::render('Data/Period/Show', [
            'period' => $period,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Period $period)
    {
        return Inertia::render('Data/Period/Edit', [
            'period' => $period,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdatePermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Period $period)
    {
        $request->validate([
            'name' => 'required',
            'number_of_timeslots' => 'required'
        ]);

        $period->update($request->all());

        return redirect()->route('period.index')
                        ->with('message', __('Period updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Period $period)
    {
        DB::transaction(function () use ($period) {
            $period->delete();

            $periods = Period::where('rank', '>', $period->rank)->get();
            foreach ($periods as $p) {
                $p->rank--;
                $p->save();
            }
        });

        return redirect()->route('period.index')
                        ->with('message', __('Period deleted successfully'));
    }
}
