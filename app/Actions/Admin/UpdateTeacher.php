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
            'specialization' => $request->specialization,
            'gender' => $request->gender,
        ]);

        return $teacher;
    }
}
