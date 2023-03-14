<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\SchoolProgram;
use App\Models\ClassSchedule;
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
            $gradelevelSections = $gradelevel->sections->whereIn('id', $schoolProgram->sections()->pluck('id')->toArray());
            foreach ($gradelevelSections as $sections) {
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

        $setdays = true;

        foreach ($timeslots as $timeslot) {
            $timeslotIds = [];
            foreach ($days as $day) {
                // if ($setdays) {
                //     $timetable->addClassday($day->id);
                // }
                if (!((($timeslot->id == 1) && ($day->rank == 1)) || (($timeslot->id == 7) && ($day->rank == 5)))) {
                    $timeslotId = 'D'.$day->rank . "T" . $timeslot->id;
                    $timetable->addTimeslot($timeslotId);
                    $timeslotIds[] = $timeslotId;
                }
                $setdays = false;
            }
            $timetable->addGroupedTimeslot($timeslot->id, $timeslotIds);
        }
        // dd($timetable->getGroupedTimeslots());
        // Set up professors
        $teachers = $schoolProgram->teachers;

        foreach ($teachers as $teacher) {
            $timetable->addTeacher($teacher->id);
        }

        // $timetable->cloneTimeslot();

        return $timetable;
    }

    /**
     * Run the timetable generation algorithm
     *
     */
    public function run()
    {
        $data = [];
        $store = [];
        $startTime = microtime(true);
        $timetable = $this->initializeTimetable(); //not needed

        $algorithm = new GeneticAlgorithm(100, 0.01, 0.9, 2, 10);

        foreach ($timetable->getGroups() as $currentGradelevel) {
            $maxGenerations = 1500;
            print "Initializing Data";
            print "\n";
            $population = $algorithm->initPopulation($timetable, $currentGradelevel);
            $algorithm->evaluatePopulation($population, $timetable, $currentGradelevel);

            // Keep track of current generation
            $generation = 1;

            while (!$algorithm->isTerminationConditionMet($population)
                && !$algorithm->isGenerationsMaxedOut($generation, $maxGenerations)) {
                $fittest = $population->getFittest(0);

                print "Generation: " . $generation . "(" . $fittest->getFitness() . ") - ";
                // print $fittest;
                print "\n";

                $population = $algorithm->initPopulation($timetable, $currentGradelevel);
                // Apply crossover
                // $population = $algorithm->crossoverPopulation($population, $currentGradelevel);

                // // Apply mutation
                // $population = $algorithm->mutatePopulation($population, $timetable, $currentGradelevel);

                // Evaluate Population
                $algorithm->evaluatePopulation($population, $timetable, $currentGradelevel);

                // Increment current
                $generation++;

                // Cool temperature of GA for simulated annealing
                // $algorithm->coolTemperature();
            }
            $solution =  $population->getFittest(0);
            $solution2 = $population->getFittest(1);

            $data[$currentGradelevel->getLevel()] = $solution->getBySection();
            $store[] = $timetable->createScheme($data[$currentGradelevel->getLevel()], $currentGradelevel);
        }

        // Update the timetable data in the DB

        $this->timetable->schedule_data1 = json_encode($store);
        $this->timetable->save();

        $this->timetable->update([
            'status' => 'COMPLETED',
        ]);

        return $solution;
    }
}
