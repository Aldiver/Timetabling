<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'bldg_letter', 'room_number'];

    public function gradelevel()
    {
        return $this->belongsTo(Gradelevel::class);
    }

    public function schoolprograms()
    {
        return $this->belongsToMany(SchoolProgram::class);
    }

    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }
}
