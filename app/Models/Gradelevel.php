<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gradelevel extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['level'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function teachers()
    {
    return $this->belongsToMany(Teacher::class, 'teacher_gl_dep')->withPivot('department_id');
    }

    public function schoolprograms()
    {
        return $this->belongsToMany(SchoolProgram::class, 'gradelevel_school_program', 'gradelevel_id', 'school_program_id');
    }
}
