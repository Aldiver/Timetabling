<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timeslot;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TimeslotController extends Controller
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
        $timeslots = (new Timeslot)->newQuery();

        if (request()->has('search')) {
            $timeslots->where('time_slot', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $timeslots->orderBy($attribute, $sort_order);
        } else {
            $timeslots->latest();
        }

        $timeslots = $timeslots->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Timeslot/Index', [
            'timeslots' => $timeslots,
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
        return Inertia::render('Data/Timeslot/Create');
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
            'time_slot' => 'required',
        ]);

        Timeslot::create($request->all());

        return redirect()->route('timeslot.index')
                        ->with('message', __('Timeslot added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Timeslot $timeslot)
    {
        return Inertia::render('Data/Timeslot/Show', [
            'timeslot' => $timeslot,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Timeslot $timeslot)
    {
        return Inertia::render('Data/Timeslot/Edit', [
            'timeslot' => $timeslot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdatePermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timeslot $timeslot)
    {
        $request->validate([
            'time_slot' => 'required',
        ]);

        $timeslot->update($request->all());

        return redirect()->route('timeslot.index')
                        ->with('message', __('Timeslot updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timeslot $timeslot)
    {
        $timeslot->delete();

        return redirect()->route('timeslot.index')
                        ->with('message', __('Timeslot deleted successfully'));
    }
}
