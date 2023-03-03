<?php

namespace App\Http\Services;

use App\Http\Services\Population;
use App\Http\Services\Schedule;

class TimetableGenerator
{
    private $schoolprogram;
    private $parentPopulation;

    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
        $this->parentPopulation = new Population($schoolprogram);
    }

    public function run()
    {
    }
}
