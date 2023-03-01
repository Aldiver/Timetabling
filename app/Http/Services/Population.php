<?php

namespace App\Http\Services;

use App\Http\Services\Schedule;

class Population
{
    private $population;
    private $id;

    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
        $this->generatePopulation();
    }

    public function generatePopulation()
    {
        // ini_set('max_execution_time', '300');
        $this->population = [];

        for ($i = 0; $i < 1; $i++) {
            $schedules = new Schedule($this->schoolprogram);
            $this->population[] = $schedules;
        }
        return $this->population;
    }

    public function getSchedules()
    {
        return $this->population;
    }

    public function getRandomSched()
    {
        return $this->population[(mt_rand(0, count($this->population)-1))];
    }

    public function sortByFitness()
    {
        //sorting algorithm
    }

    //serialize the object to be readable in frontend
    public function toArray()
    {
        return [
            'population' => $this->population,
            'id' => $this->id,
        ];
    }
}
