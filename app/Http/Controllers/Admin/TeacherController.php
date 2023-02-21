<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateTeacher;
use App\Actions\Admin\UpdateTeacher;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\Gradelevel;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TeacherController extends Controller
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
        // $gradelevels = Gradelevel::all()->pluck("level","id");
        // $departments = Department::all()->pluck("name","id");
        // $teachers = (new Teacher)->newQuery()->with('department', 'gradelevel')->paginate(5)->appends(request()->query());;
        $teachers = (new Teacher())->newQuery();

        if (request()->has('search')) {
            $teachers->where('last_name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $teachers->orderBy($attribute, $sort_order);
        } else {
            $teachers->latest();
        }
        // $teachers = $teachers->with('department', 'gradelevel')->get();
        // $teachers = $teachers->paginate(5)->onEachSide(2)->appends(request()->query());
        $teachers = $teachers->with('department', 'gradelevel')->paginate(10)->onEachSide(2)->appends(request()->query());


        return Inertia::render('Data/Teacher/Index', [
            'teachers' => $teachers,
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
        $gradelevels = Gradelevel::all()->pluck("level", "id");
        $departments = Department::all()->pluck("name", "id");

        if ($gradelevels->count() == 0) {
            return redirect()->route('section.index')
                        ->with('error', __('No Gradelevel found'));
        }

        return Inertia::render('Data/Teacher/Create', [
            'gradelevels' => $gradelevels,
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreTeacherRequest  $request
     * @param  \App\Actions\Admin\CreateTeacher  $createUser
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request, CreateTeacher $createTeacher)
    {
        $createTeacher->handle($request);
        return redirect()->route('teacher.index')
                        ->with('message', __('Teacher added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        // $roles = Role::all()->pluck("name","id");
        // $userHasRoles = array_column(json_decode($user->roles, true), 'id');

        return Inertia::render('Data/Teacher/Show', [
            'teacher' => $teacher
            // 'roles' => $roles,
            // 'userHasRoles' => $userHasRoles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        $gradelevels = Gradelevel::all()->pluck("level", "id");
        $departments = Department::all()->pluck("name", "id");

        $selectedTeacher = $teacher->with('department', 'gradelevel')->where('id', $teacher->id)->first();
        // dd($selectedTeacher);
        return Inertia::render('Data/Teacher/Edit', [
            'teacher' => $selectedTeacher,
            'gradelevels' => $gradelevels,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdateTeacherRequest  $request
     * @param  \App\Models\Teacher  $user
     * @param  \App\Actions\Admin\User\UpdateTeacher  $updateTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher, UpdateTeacher $updateTeacher)
    {
        $updateTeacher->handle($request, $teacher);

        return redirect()->route('teacher.index')
                        ->with('message', __('Teacher updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('teacher.index')
                        ->with('message', __('Teacher deleted successfully'));
    }
}
