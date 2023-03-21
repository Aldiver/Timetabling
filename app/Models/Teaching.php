<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teaching extends Model
{
    use HasFactory;

    public function load()
    {
        return $this->morphOne(Load::class, 'loadable');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
