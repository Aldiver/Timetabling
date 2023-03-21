<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLoading extends Model
{
    use HasFactory;

    protected $fillable = ['version'];

    public function teachers()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function loads()
    {
        return $this->hasMany(Load::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
}
