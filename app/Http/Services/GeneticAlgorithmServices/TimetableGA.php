<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\SchoolProgram;
use App\Models\Timetable as TimetableModel;
use App\Http\Services\GeneticAlgorithmServices\GeneticAlgorithm;

class TimetableGA
{
    /**
     * Timetable we want to run the algorithm for
     *
     * @var App\Models\Timetable
     */
    protected $timetable;

    /**
     * Create a new instance of TimetableGA class
     *
     * @param App\Models\Timetable $timetable Timetable we want to run the algorithm
     *                                        to generate
     */
    public function __construct($timetable) //TimetableModel $timetable
    {
        $this->timetable = $timetable;
    }

    /**
     * Create the problem instance for the algorithm
     *
     */
    public function initializeTimetable()
    {
        $selectedSchoolProgram = $this->timetable->schoolprograms()->first()->id; //change it later to get the schoolprogramID
        //$this->timetimeble->schoolprogramId
        // $timetable = SchoolProgram::with(['gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods'])->find($selectedSchoolProgram);

        $timetable = new Timetable();
        $schoolProgram = SchoolProgram::with(['gradelevels', 'sections', 'classdays', 'departments', 'teachers', 'periods'])->find($selectedSchoolProgram);

        /**
         * Add Grade level in data pool and group by gradelevel - sections - departments where id is gradelevel
         * Modules subjects with respective teachers
         */
        foreach ($schoolProgram->gradelevels as $gradelevel) {
            $groupId = $gradelevel->id;
            $sectionsIds = [];
            foreach ($gradelevel->sections as $sections) {
                $sectionsIds[] = $sections->id;
            }
            $moduleIds = [];
            foreach ($schoolProgram->departments as $department) {
                $teacherIds = $department->teachers()->wherePivot('gradelevel_id', $groupId)->pluck('id')->toArray();
                $moduleId = $department->subjects()->first()->id;
                $moduleIds[] = $moduleId;
                $timetable->addModule($moduleId, $groupId, $teacherIds);
            }

            $timetable->addGroup($groupId, $moduleIds, $sectionsIds);
        }

        /**
         * Set up timeslots in data pool where Monday is D1, Period 1 is T1
         * Timeslot id will now become D1T1
         */
        $days = $schoolProgram->classdays;
        $timeslots = $schoolProgram->periods;

        foreach ($days as $day) {
            foreach ($timeslots as $timeslot) {
                $timeslotId = 'D'.$day->id . "T" . $timeslot->id;
                $timetable->addTimeslot($timeslotId);
            }
        }

        // Set up professors
        $teachers = $schoolProgram->teachers;

        foreach ($teachers as $teacher) {
            $timetable->addTeacher($teacher->id);
        }

        // dd($timetable);

        return $timetable;
    }

    /**
     * Run the timetable generation algorithm
     *
     */
    public function run()
    {
        $startTime = microtime(true);
        $maxGenerations = 1500;

        $timetable = $this->initializeTimetable(); //not needed

        $algorithm = new GeneticAlgorithm(100, 0.01, 0.9, 2, 10);

        $population = $algorithm->initPopulation($timetable); //timetable is the schoolprogram with relations

        $algorithm->evaluatePopulation($population); //each individual man already has a method to calculate conflicts

        // Keep track of current generation
        $generation = 1;

        while (!$algorithm->isTerminationConditionMet($population)
            && !$algorithm->isGenerationsMaxedOut($generation, $maxGenerations)) {
            $fittest = $population->getFittest(0);

            // Apply crossover
            $population = $algorithm->crossoverPopulation($population);

            // Apply mutation
            $population = $algorithm->mutatePopulation($population, $timetable);

            // Evaluate Population
            $algorithm->evaluatePopulation($population);

            // Increment current
            $generation++;

            // Cool temperature of GA for simulated annealing
            $algorithm->coolTemperature();

            $solution =  $population->getFittest(0);
            $elapsedTime = microtime(true) - $startTime;
            dd($elapsedTime, $solution->getFitness());
        }

        $solution =  $population->getFittest(0);

        // Update the timetable data in the DB
        // $this->timetable->update([
        //     'chromosome' => $solution->getChromosomeString(),
        //     'fitness' => $solution->getFitness(),
        //     'generations' => $generation,
        //     'scheme' => $scheme,
        //     'status' => 'COMPLETED'
        // ]);

        return $solution;
    }
}
