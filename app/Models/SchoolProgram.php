<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProgram extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'school_year'
    ];

    public function gradelevels()
    {
        return $this->belongsToMany(Gradelevel::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function classdays()
    {
        return $this->belongsToMany(Classday::class);
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
}
