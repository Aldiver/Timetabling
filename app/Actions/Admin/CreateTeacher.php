<?php

namespace App\Actions\Admin;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateTeacher
{
    public function handle(Request $request): Teacher
    {
        $teacher = Teacher::create([
            'teacher_id' => $request->teacher_id,
            'last_name' => Str::title($request->last_name),
            'first_name' => Str::title($request->first_name),
            'middle_name' => Str::title($request->middle_name),
            'grade_level_assigned' => $request->grade_level_assigned,
            'special_task' => $request->special_task,
            'image' => $request->image,
            'email' => $request->email
        ]);

        // $roles = $request->roles ?? [];
        // $user->assignRole($roles);

        return $teacher;
    }
}
