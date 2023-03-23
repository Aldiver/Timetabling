<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLoading extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'timetable_id',
        'version',
        'load',
        'teacher_name'
    ];
}
