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
    protected $fillable = ['name', 'status'];

    protected $casts = [
        'schedule_data1' => 'json',
        'schedule_data2' => 'json',
    ];

    // Define the properties of your custom class object
    public $bySection;

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
