<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'full_name',
        'last_name',
        'first_name',
        'middle_name',
        'specialization',
        'gender'
    ];

    /**
     * Get all of the department for the Teacher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function department()
    {
        return $this->belongsToMany(Department::class, 'teacher_gl_dep');
    }

    public function gradelevel()
    {
        return $this->belongsToMany(Gradelevel::class, 'teacher_gl_dep')->withPivot('department_id');
    }

    public function schoolprogram()
    {
        return $this->belongsToMany(SchoolProgram::class, 'school_program_teacher', 'teacher_id', 'school_program_id');
    }

    public function teacherLoadings()
    {
        return $this->hasMany(TeacherLoading::class);
    }
}
