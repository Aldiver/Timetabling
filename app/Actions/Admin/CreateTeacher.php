<?php

namespace App\Actions\Admin;

use App\Models\Teacher;
use App\Models\Gradelevel;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateTeacher
{
    public function handle(Request $request): Teacher
    {
        if($teacher = Teacher::create([
            'full_name' => Str::title($request->last_name).' '.Str::title($request->first_name).' '.Str::title($request->middle_name),
            'last_name' => Str::title($request->last_name),
            'first_name' => Str::title($request->first_name),
            'middle_name' => Str::title($request->middle_name),
            'specialization' => $request->specialization,
            'gender' => $request->gender,
        ])){
        $gradelevel = Gradelevel::where('level', $request->gradelevel)->first();
        $department = Department::where('name', $request->department)->first();
        $teacher->gradelevel()->attach($gradelevel, ['department_id' => $department->id]);
        }



        return $teacher;
    }
}
