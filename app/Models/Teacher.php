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

    public static function getRandomTeacher($schoolprogram, $gradelevel, $department)
    {
        return self::whereHas('gradelevel', function ($query) use ($gradelevel) {
            $query->where('id', $gradelevel->id);
        })
                ->whereHas('department', function ($query) use ($department) {
                    $query->where('id', $department->id);
                })
                ->whereHas('schoolprograms', function ($query) use ($schoolprogram) {
                    $query->where('id', $schoolprogram->id);
                })
                ->get()
                ->random();
    }
}
