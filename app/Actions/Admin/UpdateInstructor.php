<?php

namespace App\Actions\Admin;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateInstructor
{
    public function handle(Request $request, Instructor $instructor): Instructor
    {        
        $instructor->update([
            'teacher_id' => $request->teacher_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'grade_level_assigned' => $request->grade_level_assigned,
            'special_task' => $request->special_task,
            'image' => $request->image,
            'email' => $request->email
        ]);

        return $instructor;
    }
}
