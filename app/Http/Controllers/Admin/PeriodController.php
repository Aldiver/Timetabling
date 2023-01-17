<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;
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

        if (request()->has('search')) {
            $periods->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $periods->orderBy($attribute, $sort_order);
        } else {
            $periods->latest();
        }

        $periods = $periods->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Period/Index', [
            'periods' => $periods,
            'filters' => request()->all('search'),
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
        return Inertia::render('Data/Period/Create');
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
            'name' => 'required',
            'number_of_timeslots' => 'required'
        ]);

        Period::create($request->all());

        return redirect()->route('period.index')
                        ->with('message', __('Period added.'));
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
        $period->delete();

        return redirect()->route('period.index')
                        ->with('message', __('Period deleted successfully'));
    }
}
