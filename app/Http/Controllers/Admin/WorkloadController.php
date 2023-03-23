<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TeacherLoading;

class WorkloadController extends Controller
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
        $teacherLoads = (new TeacherLoading())->newQuery();

        $timetables = Timetable::where('status', 'COMPLETED')->get();

        if (request()->has('search')) {
            $timetableName = request()->input('search');
            $timetable = Timetable::where('name', $timetableName)->first();
            // dd("hello");
            $teacherLoads = TeacherLoading::where('timetable_id', $timetable->id)
                                 ->where('version', 1)
                                 ->get();
            // dd($teachers, $timetable->id, 1);
        }

        return Inertia::render('Data/Workload/Index', [
            'timetables' => $timetables,
            'teachers' => $teacherLoads,
            'filters' => request()->all('filter'),
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }
}
