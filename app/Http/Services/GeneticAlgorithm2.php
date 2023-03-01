<?php

namespace App\Http\Services;

use App\Http\Services\Population;
use App\Http\Services\Schedule;

class GeneticAlgorithm
{
    private $schoolprogram;
    //instantiate GA with the initial data
    private $ELITISM; //add more
    public function __construct($schoolprogram)
    {
        $this->schoolprogram = $schoolprogram;
    }

    public function evolvePopulation($population): Population
    {
        return $this->mutatePopulation($this->crossoverPopulation($population));
    }
    private function crossoverPopulation($population): Population
    {
        $crossPopulation = new Population(1, $this->schoolprogram);
        $ELITISM = 1; //based it later
        $CROSSOVER_RATE = .9;
        //check this well
        foreach ($crossPopulation->getSchedules() as $index => $schedule) {
            if ($index < $ELITISM) {
                $crossPopulation->getSchedules()[$index] = $population[$index];
            } else {
                if ($CROSSOVER_RATE > mt_rand() / mt_getrandmax()) {
                    $Schedule1 = $this->tournamentPopulation($population)->getSchedules()[0];
                    $Schedule2 = $this->tournamentPopulation($population)->getSchedules()[0];
                    $crossPopulation->getSchedules()[$index] = $this->crossoverSchedule($Schedule1, $Schedule2);
                } else {
                    $crossPopulation->getSchedules()[$index] = $population[$index];
                }
            }
        }
        return $crossPopulation;
    }

    private function crossoverSchedule($schedule1, $schedule2): Schedule
    {
        $crossSchedule = new Schedule(); //faster if instantiate empty
        //check this part
        foreach ($crossSchedule as $index => $schedule) {
            if (mt_rand() / mt_getrandmax() > .5) {
                $crossSchedule[$index] = $schedule1[$index];
            } else {
                $crossSchedule[$index] = $schedule2[$index];
            }
        }
        return $crossSchedule;
    }

    private function mutatePopulation($population): Population
    {
        $mutatePop = new Population(); //empty nalang
        $schedules = $mutatePop->getSchedules();
        $ELITE_SCHEDULES = 1;

        foreach ($mutatePop->getSchedules() as $index => $schedule) {
            if ($index < $ELITE_SCHEDULES) {
                $mutatePop->getSchedules()[$index] = $population->getSchedules()[$index];
            } else {
                $mutatePop->getSchedules()[$index] = $this->mutateSchedule($population->getSchedules()[$index]);
            }
        }
        return $mutatePop;
    }

    private function mutateSchedule($mutateSchedule): Schedule
    {
        $schedules = new Schedule(0, $this->schoolprogram); //instantiate new schedule
        $MUTATION_RATE = .1;
        foreach ($schedules as $index => $classes) {
            if ($MUTATION_RATE > mt_rand() / mt_getrandmax()) { //sample values
                $mutateSchedule->getSchedules()[$index] = $classes;
            }
        }
        return $mutateSchedule;
    }

    private function tournamentPopulation($population): Population
    {
        $TOURNAMENT_SELECTION_SIZE = 3;
        $tournamentPop = new Population(); //better if create empty
        foreach ($tournamentPop->getSchedules() as $index => $_) {
            $randomKey = array_rand($population);
            $randomValue = $population[$randomKey];
            $tournamentPop->getSchedules()[$index] = $randomValue;
        }
        return $tournamentPop;
    }
}
