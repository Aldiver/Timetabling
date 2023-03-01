<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The schoolprograms that belong to the Timetable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schoolprograms()
    {
        return $this->belongsToMany(SchoolProgram::class);
    }
}
