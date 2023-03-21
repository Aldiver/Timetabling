<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    use HasFactory;

    public function teacherLoading()
    {
        return $this->belongsTo(TeacherLoading::class);
    }

    public function loadable()
    {
        return $this->morphTo();
    }
}
