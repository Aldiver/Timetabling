<?php

namespace App\Actions\Admin;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateTeacher
{
    public function handle(Request $request, Teacher $teacher): Teacher
    {
        $teacher->update([

            // update later
            'last_name' => Str::title($request->last_name),
            'first_name' => Str::title($request->first_name),
            'middle_name' => Str::title($request->middle_name),
            'grade_level_assigned' => $request->grade_level_assigned,
            'special_task' => $request->special_task,
            'image' => $request->image,
            'email' => $request->email
        ]);

        return $teacher;
    }
}
