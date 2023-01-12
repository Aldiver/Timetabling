<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateInstructor;
use App\Actions\Admin\UpdateInstructor;
use App\Http\Requests\Admin\StoreInstructorRequest;
use App\Http\Requests\Admin\UpdateInstructorRequest;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class InstructorController extends Controller
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
        $instructors = (new Instructor)->newQuery();

        if (request()->has('search')) {
            $instructors->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $instructors->orderBy($attribute, $sort_order);
        } else {
            $instructors->latest();
        }

        $instructors = $instructors->paginate(5)->onEachSide(2)->appends(request()->query());

        return Inertia::render('Data/Instructor/Index', [
            'instructors' => $instructors,
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
        //$roles = Role::all()->pluck("name","id"); grade level

        // return Inertia::render('Data/Instructor/Create', [
        //     'roles' => $roles,
        // ]);

        return Inertia::render('Data/Instructor/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreInstructorRequest  $request
     * @param  \App\Actions\Admin\CreateInstructor  $createUser
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInstructorRequest $request, CreateInstructor $createInstructor)
    {
        $createInstructor->handle($request);

        return redirect()->route('instructor.index')
                        ->with('message', __('Instructor added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        // $roles = Role::all()->pluck("name","id");
        // $userHasRoles = array_column(json_decode($user->roles, true), 'id');

        return Inertia::render('Data/Instructor/Show', [
            'instructor' => $instructor
            // 'roles' => $roles,
            // 'userHasRoles' => $userHasRoles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function edit(Instructor $instructor)
    {
        // $roles = Role::all()->pluck("name","id");
        // $userHasRoles = array_column(json_decode($user->roles, true), 'id');

        return Inertia::render('Data/Instructor/Edit', [
            'instructor' => $instructor,
            // 'roles' => $roles,
            // 'userHasRoles' => $userHasRoles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdateInstructorRequest  $request
     * @param  \App\Models\Instructor  $user
     * @param  \App\Actions\Admin\User\UpdateInstructor  $updateInstructor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor, UpdateInstructor $updateInstructor)
    {
        $updateInstructor->handle($request, $instructor);

        return redirect()->route('instructor.index')
                        ->with('message', __('Instructor updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        $instructor->delete();

        return redirect()->route('instructor.index')
                        ->with('message', __('Instructor deleted successfully'));
    }
}
