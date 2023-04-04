<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TeacherLoading;

use App\Http\Services\TeacherLoading\AssignLoads;

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
            $teacherLoads1 = TeacherLoading::where('timetable_id', $timetable->id)
                                 ->where('version', 1)
                                 ->get();
            $teacherLoads2 = TeacherLoading::where('timetable_id', $timetable->id)
            ->where('version', 2)
            ->get();
        }

        return Inertia::render('Data/Workload/Index', [
            'timetables' => $timetables,
            'teachersData1' => $teacherLoads1 ?? [],
            'teachersData2' => $teacherLoads2 ?? [],
            'filters' => $timetable ?? request()->all('filter'),
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }

    public function assignLoads($id)
    {
        $timetable = Timetable::find($id);

        $initializeTeacherLoading = new AssignLoads($timetable);
        $teacherData = $initializeTeacherLoading->clearLoads();
        // Filter the teacherData collection to get only the items with version 1
        $teacherLoads1 = $teacherData->where('version', 1)->values()->all();
        $teacherLoads2 = $teacherData->where('version', 2)->values()->all();

        $adminLoads = Admin::all();

        return Inertia::render('Data/Workload/Edit', [
            'adminLoads' => $adminLoads,
            'teachersData1' => $teacherLoads1,
            'teachersData2' => $teacherLoads2,
            'can' => [
                'create' => Auth::user()->can('permission create'),
                'edit' => Auth::user()->can('permission edit'),
                'delete' => Auth::user()->can('permission delete'),
            ]
        ]);
    }


    public function store(Request $request)
    {
        $adminCount = Admin::count();
        // dd($request->adminLoad2[0]['name']);
        // dd($request->assignedTeachers, $request->assignedTeachers2);
        for ($i = 0; $i < $adminCount; $i++) {
            $toUpdate = TeacherLoading::find($request->assignedTeachers2[$i]['id']);
            $load = json_decode($toUpdate->load, true);
            $load['Admin'] = $request->adminLoad2[$i]['name'];
            $toUpdate->update([ 'load' => json_encode($load)]);

            $toUpdate2 = TeacherLoading::find($request->assignedTeachers[$i]['id']);
            $load = json_decode($toUpdate2->load, true);
            $load['Admin'] = $request->adminLoad[$i]['name'];
            $toUpdate2->update([ 'load' => json_encode($load)]);
        }
        return redirect()->route('workload.index');
    }
}
