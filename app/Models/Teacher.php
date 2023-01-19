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
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
