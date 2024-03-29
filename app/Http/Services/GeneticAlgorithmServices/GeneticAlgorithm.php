<?php

namespace App\Http\Services\GeneticAlgorithmServices;

class GeneticAlgorithm
{
    /**
     * This is the number of individuals in the population
     *
     * @var int
     */
    private $populationSize;

    /**
     * This is the probability in which a specific gene in a solution’s
     * chromosome will be mutated
     *
     * @var double
     */
    private $mutationRate;

    /**
     * This is the frequency in which crossover is applied
     *
     * @var double
     */
    private $crossoverRate;

    /**
     * This represents the number of individuals to be
     * considered as elite and skipped during crossover
     *
     * @var integer
     */
    private $elitismCount;

    /**
     * Size of the tournament
     *
     * @var int
     */
    private $tournamentSize;

    /**
     * Temperature for simulated annealing
     *
     * @var int
     */
    private $temperature;

    /**
     * Cooling rate for simulated annealing
     *
     * @var int
     */
    private $coolingRate;

    private $bestSolutions;
    /**
     * Create a new instance of this class
     */
    public function __construct($populationSize, $mutationRate, $crossOverRate, $elitismCount, $tournamentSize)
    {
        $this->populationSize = $populationSize;
        $this->mutationRate = $mutationRate;
        $this->crossoverRate = $crossOverRate;
        $this->elitismCount = $elitismCount;
        $this->tournamentSize = $tournamentSize;
        $this->temperature = 1.0;
        $this->coolingRate = 0.001;
        $this->bestSolutions = 0;
    }

    /**
     * Initialize a population
     *
     * @param Timetable $timetable Timetable for generating individuals
     */
    public function initPopulation($timetable, $currentGradelevel)
    {
        $population = new Population($this->populationSize, $timetable, $currentGradelevel);

        return $population;
    }

    /**
     * Get the temperature
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Cool temperature
     *
     */
    public function coolTemperature()
    {
        $this->temperature *= (1 - $this->coolingRate);
    }

    /**
     * Calculate the fitness of a given individual
     *
     * @param Individual $individual The individual
     * @return double The fitness of the individual
     */
    public function calculateFitness($individual, $timetable, $currentGradelevel)
    {
        $timetable = clone $timetable;

        $timetable->createClasses($individual, $currentGradelevel);
        $clashes = $timetable->calcClashes($individual, $currentGradelevel);
        $fitness = 1.0 / ($clashes + 1);
        print "fitness: " .$fitness ."\n";
        $individual->setFitness($fitness);
        return $fitness;
    }

    /**
     * Evaluate a given population
     *
     * @param Population $population The population to evaluate
     * @param Timetable $timetable Timetable data
     */
    public function evaluatePopulation($population, $timetable, $currentGradelevel)
    {
        print "Evaluating Population \n";
        $populationFitness = 0;

        $individuals = $population->getIndividuals();

        $count = 0;
        foreach ($individuals as $individual) {
            print "Gradelevel " .$currentGradelevel->getId() ." Calculating fitness... ". $count++ ."\n";
            $populationFitness += $this->calculateFitness($individual, $timetable, $currentGradelevel);
        }

        $population->setPopulationFitness($populationFitness);
    }

    /**
     * Determine whether the termination condition has been met
     * For this problem, this occurs when we get an individual with
     * a fitness of 1.0
     *
     * @param Population $population Population we are evaluating
     * @return boolean The truth value of this check
     */
    public function isTerminationConditionMet($population)
    {
        return $population->getFittest(0)->getFitness() == 1;
    }

    /**
     * Determine whether we have reached the max generations we want to
     * iterate through
     *
     * @param int $generations Number of generations
     * @param int $maxGenerations Max generations
     */
    public function isGenerationsMaxedOut($generations, $maxGenerations)
    {
        return $generations > $maxGenerations;
    }

    /**
     * Select a parent from a population to be used in a crossover
     * with some other individual
     *
     * The technique used here is tournament selection method
     *
     * @param Population $population The population
     * @return Individual The selected parent
     */
    public function selectParent($population)
    {
        $tournament = new Population();

        $population->shuffle();

        for ($i = 0; $i < $this->tournamentSize; $i++) {
            $participant = $population->getIndividual($i);
            $tournament->setIndividual($i, $participant);
        }

        return $tournament->getFittest(0);
    }

    /**
     * Perform  a crossover on a population's individuals
     *
     * @param Population $population The population
     * @return Population $newPopulation The resulting population
     */
    public function crossoverPopulation($population, $currentGradelevel)
    {
        print "Crossover Population \n";
        $newPopulation = new Population($population->size(), null, $currentGradelevel);

        for ($i = 0; $i < $population->size(); $i++) {
            $parentA = $population->getFittest($i);

            $random = mt_rand() / mt_getrandmax();
            if (($this->crossoverRate > $random) && ($i > $this->elitismCount)) {
                // Initialise offspring
                $offspring = Individual::random($parentA->getChromosomeLength());

                $parentB = $this->selectParent($population);

                $swapPointA = mt_rand(0, $parentB->getChromosomeLength());
                $swapPointB = mt_rand(0, $parentB->getChromosomeLength());

                // if ($swapPointA > $swapPointB) {
                //     $temp = $swapPointA;
                //     $swapPointA = $swapPointB;
                //     $swapPointB = $temp;
                // }
                // || $j >= $swapPointB
                for ($j = 0; $j < $parentA->getChromosomeLength(); $j++) {
                    if ($j < $swapPointA) {
                        $offspring->setGene($j, $parentA->getGene($j));
                    } else {
                        $offspring->setGene($j, $parentB->getGene($j));
                    }
                }

                $newPopulation->setIndividual($i, $offspring);
            } else {
                // Add to population without crossover
                $newPopulation->setIndividual($i, $parentA);
            }
        }

        return $newPopulation;
    }

    /**
     * Perform a mutation on the individuals of the given population
     *
     * @param Population $population The population to mutate
     */
    public function mutatePopulation($population, $timetable, $currentGradelevel)
    {
        print "Mutate Population \n";
        $newPopulation = new Population();
        $bestFitness = $population->getFittest(0)->getFitness();

        for ($i = 0; $i < $population->size(); $i++) {
            $individual = $population->getFittest($i);
            $randomIndividual = new Individual($timetable, $currentGradelevel);

            // Calculate adaptive mutation rate
            $adaptiveMutationRate = $this->mutationRate;

            if ($individual->getFitness() > $population->getAvgFitness()) {
                $fitnessDelta1 = $bestFitness - $individual->getFitness();
                $fitnessDelta2 = $bestFitness - $population->getAvgFitness();
                $adaptiveMutationRate = ($fitnessDelta1 / $fitnessDelta2) * $this->mutationRate;
            }

            if ($i > $this->elitismCount) {
                for ($j = 0; $j < $individual->getChromosomeLength(); $j++) {
                    $random = mt_rand() / mt_getrandmax();

                    if (($adaptiveMutationRate * $this->temperature) > $random) {
                        $individual->setGene($j, $randomIndividual->getGene($j));
                    }
                }
            }

            $newPopulation->setIndividual($i, $individual);
        }

        return $newPopulation;
    }
}
