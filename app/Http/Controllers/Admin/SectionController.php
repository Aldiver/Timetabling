<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use App\Models\Gradelevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SectionController extends Controller
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
        $gradelevels = Gradelevel::all()->pluck("level","id");
        $sections = (new Section)->newQuery();

        if (request()->has('search')) {
            $sections->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $sections->orderBy($attribute, $sort_order);
        } else {
            $sections->latest();
        }

        $sections = $sections->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Section/Index', [
            'sections' => $sections,
            'filters' => request()->all('search'),
            'gradelevels' => $gradelevels,
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
        $gradelevels = Gradelevel::all()->pluck("level","id");

        if($gradelevels->count() == 0){
            return redirect()->route('section.index')
                        ->with('error', __('No Gradelevel found'));
        }
        return Inertia::render('Data/Section/Create', [
            'gradelevels' => $gradelevels,
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
        $request->validate([
            'name' => 'required',
            'bldg_letter' => 'required',
            'room_number' => 'required',
            'gradelevels' => 'required'
        ]);

        $gradelevel = Gradelevel::where('level', $request->gradelevels)->first();
        $section = Section::create([
            'name' => $request->name,
            'bldg_letter' =>$request->bldg_letter,
            'room_number' => $request->room_number,
        ]);
        $gradelevel->sections()->save($section);


        return redirect()->route('section.index')
                        ->with('message', __('section added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        return Inertia::render('Data/Section/Show', [
            'section' => $section,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        return Inertia::render('Data/Section/Edit', [
            'section' => $section,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required',
            'bldg_letter' => 'required',
            'room_number' => 'required'
        ]);

        $section->update($request->all());

        return redirect()->back()
                        ->with('message', __('section updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('section.index')
                        ->with('message', __('Section deleted successfully'));
    }
}
