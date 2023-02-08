<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubjectController extends Controller
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
        $departments = Department::all()->pluck("name", "id");
        $subjects = (new Subject())->newQuery();
        // $departments = Department::all()->pluck("name","id");
        if (request()->has('search')) {
            $subjects->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $subjects->orderBy($attribute, $sort_order);
        } else {
            $subjects->latest();
        }

        $subjects = $subjects->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Subject/Index', [
            'subjects' => $subjects,
            'filters' => request()->all('search'),
            'departments' => $departments,
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
        $departments = Department::all()->pluck("name", "id");

        if ($departments->count() == 0) {
            return redirect()->route('subject.index')
                        ->with('error', __('No departments found'));
        }
        return Inertia::render('Data/Subject/Create', [
            'departments' => $departments,
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
            'hours_per_week' => 'required',
            'departments' => ['required' , 'string'],
        ]);

        $department = Department::where('name', $request->departments)->first();
        $subject = Subject::create([
            'name' => $request->name,
            'hours_per_week' => $request->hours_per_week,
        ]);
        $department->subjects()->save($subject);

        return redirect()->route('subject.index')
                        ->with('message', __('subject added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        return Inertia::render('Data/Subject/Show', [
            'subject' => $subject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        return Inertia::render('Data/Subject/Edit', [
            'subject' => $subject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required',
            'hours_per_week' => 'required',
        ]);

        $subject->update($request->all());

        return redirect()->route('subject.index')
                        ->with('message', __('subject added.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subject.index')
                        ->with('message', __('Subject deleted successfully'));
    }
}
