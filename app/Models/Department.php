<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The roles that belong to the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_gl_dep')->withPivot('gradelevel_id');
    }

    public function schoolprograms()
    {
        return $this->belongsToMany(SchoolProgram::class, 'department_school_program', 'department_id', 'school_program_id');
    }
}
