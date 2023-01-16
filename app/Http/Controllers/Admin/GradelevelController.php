<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gradelevel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GradelevelController extends Controller
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
        $gradelevels = (new Gradelevel)->newQuery();

        if (request()->has('search')) {
            $gradelevels->where('level', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $gradelevels->orderBy($attribute, $sort_order);
        } else {
            $gradelevels->latest();
        }

        $gradelevels = $gradelevels->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Gradelevel/Index', [
            'gradelevels' => $gradelevels,
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
        return Inertia::render('Data/Gradelevel/Create');
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
            'level' => 'required',
        ]);

        Gradelevel::create($request->all());

        return redirect()->route('gradelevel.index')
                        ->with('message', __('Grade level added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Gradelevel $gradelevel)
    {
        return Inertia::render('Data/Gradelevel/Show', [
            'gradelevel' => $gradelevel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Gradelevel $gradelevel)
    {
        return Inertia::render('Data/Gradelevel/Edit', [
            'gradelevel' => $gradelevel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdatePermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gradelevel $gradelevel)
    {
        $request->validate([
            'level' => 'required',
        ]);

        $gradelevel->update($request->all());

        return redirect()->route('gradelevel.index')
                        ->with('message', __('Grade level updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permission.index')
                        ->with('message', __('Permission deleted successfully'));
    }
}
