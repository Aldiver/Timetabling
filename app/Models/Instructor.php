<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'last_name',
        'first_name',
        'middle_name',
        'grade_level_assigned',
        'special_task',
        'image',
        'email',
    ];
}