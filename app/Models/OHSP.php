<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OHSP extends Model
{
    use HasFactory;

    public function load()
    {
        return $this->morphOne(Load::class, 'loadable');
    }
}
