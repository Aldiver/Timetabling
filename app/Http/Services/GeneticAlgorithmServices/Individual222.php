<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Http\Services\ClassData;
use App\Http\Services\Subject;
use App\Http\Services\Period;
use App\Models\Teacher;

class Individual
{
    private $schoolprogram;
    private $fitness;
    private $chromosome;
    private $id;
    private $conflicts;
    private $teacher_conflicts;
    private $fitnessChanged = true;
    private $teacher_loading = [];

    public function __construct($schoolprogram = null, $currentGradelevel = null)
    {
        $this->schoolprogram = $schoolprogram;
        if ($schoolprogram) {
            $this->generateSchedule($schoolprogram, $currentGradelevel);
        } else {
            $this->chromosome = [];
        }
    }

    public function generateSchedule($timetable, $currentGradelevel)
    {
        // ini_set('max_execution_time', '300');
        //Initialize constants for each class schedules
        $newChromosome = [];
        $chromosomeIndex = 0;

        $group = $currentGradelevel;
        foreach ($group->getSectionIds() as $sections) {
            $departments = collect($group->getModuleIds());
            foreach ($timetable->getGroupedTimeslots() as $id => $periodIndex) {
                if ($group->getId() > 2) {
                    $selectedModule = $departments->random();
                    while ($timetable->getModule($selectedModule, $group->getId())->isAdvisory()) {
                        $selectedModule = $departments->random();
                    }
                } else {
                    $selectedModule = $departments->random();
                }
                $departments = $departments->reject($selectedModule);

                //teacher selection
                $module = $timetable->getModule($selectedModule, $group->getId());
                $teacher = $module->getRandomTeacherId();
                $newChromosome[$chromosomeIndex] = $teacher;
                $chromosomeIndex++;

                //select timeslot
                for ($i = 1; $i <= $module->getSlots($group->getId()); $i++) {
                    // Add random time slot
                    if ($module->getSlots($group->getId()) == 4) {
                        $timeslotId = $timetable->getRandomGroupedTimeslot($id);
                        $newChromosome[$chromosomeIndex] = $timeslotId;
                    } else {
                        $timeslotId = $timetable->getRandomTimeslot()->getId();
                        $newChromosome[$chromosomeIndex] = $timeslotId;
                    }
                }
                $chromosomeIndex++;
            }
            $selectedModule = $departments->random();
            $module = $timetable->getModule($selectedModule, $group->getId());
            $teacher = $module->getRandomTeacherId();
            $newChromosome[$chromosomeIndex] = $teacher;
            $chromosomeIndex++;

            //select timeslot
            for ($i = 1; $i <= $module->getSlots($group->getId()); $i++) {
                // Add random time slot
                $timeslotId = $timetable->getRandomTimeslot()->getId();
                $newChromosome[$chromosomeIndex] = $timeslotId;
            }
            $chromosomeIndex++;
            $timetable->resetAllocatedTimeslots();
        }
        // dd($newChromosome);
        $this->chromosome = $newChromosome;
    }

    /**
     * Create a new individual with a randomised chromosome
     *
     * @param int $chromosomeLength Desired chromosome length
     */
    public static function random($chromosomeLength)
    {
        $individual = new Individual();

        for ($i = 0; $i < $chromosomeLength; $i++) {
            $individual->setGene($i, mt_rand(0, 1));
        }

        return $individual;
    }

    /**
     * Get the individual's chromosome
     *
     * @return array The chromosome
     */
    public function getChromosome()
    {
        return $this->chromosome;
    }

    /**
     * Get the length of the individual's chromosome
     *
     * @return int The length
     */
    public function getChromosomeLength()
    {
        return count($this->chromosome);
    }

    /**
     * Fix a gene at the given location of the chromosome
     *
     * @param int $index The location to insert the gene
     * @param int $gene The gene
     */
    public function setGene($index, $gene)
    {
        $this->chromosome[$index] = $gene;
    }

    /**
     * Get the gene at the specified location
     *
     * @param $index The location to get the gene at
     * @return int The bit representing the gene at that location
     */
    public function getGene($index)
    {
        return $this->chromosome[$index];
    }

    /**
     * Set the fitness param for this individual
     *
     * @param double $fitness The fitness of this individual
     */

    public function toArray()
    {
        return [
            'schoolprogram' => $this->schoolprogram,
            'fitness' => $this->fitness,
            'chromosome' => $this->chromosome,
            'id' => $this->id,
            'teacher_loading' => $this->teacher_loading,
            'conflicts' => $this->conflicts,
            'teacher_conflicts' => $this->teacher_conflicts,
        ];
    }

    private function getFilteredTeachers($gradelevel, $selectedDepartment, $isAdvisory)
    {
        $teacher_loading = $this->teacher_loading;

        $filteredTeachers = $this->schoolprogram->teachers()
                    ->whereHas('gradelevel', function ($query) use ($gradelevel) {
                        $query->where('id', $gradelevel->id);
                    })->whereHas('department', function ($query) use ($selectedDepartment) {
                        $query->where('id', $selectedDepartment->id);
                    })->pluck('full_name');

        if ($isAdvisory) {
            $minCount = $filteredTeachers->reject(function ($teacher) use ($teacher_loading) {
                return in_array('Advisory', $teacher_loading[$teacher] ?? []);
            })->min(function ($teacher) use ($teacher_loading) {
                return count($teacher_loading[$teacher] ?? []);
            });

            return $filteredTeachers->reject(function ($teacher) use ($teacher_loading, $minCount) {
                return in_array('Advisory', $teacher_loading[$teacher] ?? []) || count($teacher_loading[$teacher] ?? []) > $minCount;
            })->values();
        }

        $minCount = $filteredTeachers->min(function ($teacher) use ($teacher_loading) {
            return count($teacher_loading[$teacher] ?? []);
        });

        return $filteredTeachers->filter(function ($teacher) use ($teacher_loading, $minCount) {
            return count($teacher_loading[$teacher] ?? []) == $minCount;
        });
    }
    private function assignAdminLoads($teachers, $adminLoads)
    {
        $teachers = $teachers->shuffle();
        // dd($teachers);
        while ($adminLoads->isNotEmpty()) {
            $randomTeacher = $teachers->random();
            if (array_key_exists($randomTeacher->id, $this->teacher_loading)) {
                if (!in_array('Advisory', $this->teacher_loading[$randomTeacher->id])) {
                    array_push($this->teacher_loading[$randomTeacher->id], $adminLoads->shift());
                    foreach ($teachers as $key => $value) {
                        if ($randomTeacher == $value) {
                            $teachers->forget($key);
                        }
                    }
                }
            } else {
                $this->teacher_loading[$randomTeacher->id] = [$adminLoads->shift()];
                foreach ($teachers as $key => $value) {
                    if ($randomTeacher == $value) {
                        $teachers->forget($key);
                    }
                }
            }
        }
        // dd($this->teacher_loading);
    }

    private function assignRegularLoads($teacher, $load)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            array_push($this->teacher_loading[$teacher], $load);
        } else {
            $this->teacher_loading[$teacher] = [$load];
        }
    }

    private function checkIfAdvisor($teacher)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            if (in_array('Advisory', $this->teacher_loading[$teacher])) {
                return true;
            }
        }

        return false;
    }

    private function loadCount($teacher)
    {
        if (array_key_exists($teacher, $this->teacher_loading)) {
            return count($this->teacher_loading[$teacher]);
        }
        return 0;
    }

    private function assignOHSPLoads()
    {
        $departments = $this->schoolprogram->departments->shuffle();

        $advisoryLoad = collect(['Advisory', 'Advisory', 'Advisory', 'Advisory']);
        $ohspLoad = "OHSP"; //get model
        $teachers = [];
        $gradelevel = 0;

        foreach ($departments as $department) {
            $teacher1 = $department->teachers()
            ->whereIn('gradelevel_id', [1 + $gradelevel])->inRandomOrder()->first();

            $teacher2 = $department->teachers()
            ->whereIn('gradelevel_id', [3 + $gradelevel])->inRandomOrder()->first();

            if ($advisoryLoad->isNotEmpty()) {
                $this->teacher_loading[$teacher1->id] = [$advisoryLoad->shift(), $ohspLoad, $ohspLoad];
                $this->teacher_loading[$teacher2->id] = [$advisoryLoad->shift(), $ohspLoad, $ohspLoad];
            } else {
                $this->teacher_loading[$teacher1->id] = [$ohspLoad, $ohspLoad];
                $this->teacher_loading[$teacher2->id] = [$ohspLoad, $ohspLoad];
            }
            $gradelevel = ($gradelevel === 0) ? 1 : 0;
        }
        // dd($this->teacher_loading);
    }

    private function createScheduleBlocks($periodCount, $classdayCount)
    {
        $reservedClassdays = [];

        $reservedClassdays[0][0] = true;  // Reserve first classday of first period
        $reservedClassdays[$periodCount-1][$classdayCount-1] = true;  // Reserve last classday of last period

        // Reserve random classdays between first and last periods
        $reservedDays = [];
        $reservedperiods = [];
        for ($i = 0; $i < 4; $i++) {
            $randomPeriodIndex = mt_rand(1, $periodCount - 2);
            $randomClassdayIndex = mt_rand(0, $classdayCount - 1);

            if (!isset($reservedClassdays[$randomPeriodIndex][$randomClassdayIndex])
            && !in_array($randomClassdayIndex, $reservedDays)
            && !in_array($randomPeriodIndex, $reservedperiods)) {
                $reservedDays [] = $randomClassdayIndex;
                $reservedperiods [] = $randomPeriodIndex;
                $reservedClassdays[$randomPeriodIndex][$randomClassdayIndex] = true;
            } else {
                // If the block is already reserved, decrement $i and try again
                $i--;
            }
        }

        return $reservedClassdays;
    }

    private function checkAvailability($scheduleBlock, $period, $classdayCount)
    {
        $count = 0;
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period)][$i])) {
                $count++;
            }
        }
        if ($count==5) {
            return true;
        }
        return false;
    }

    private function checkPeriodFull($scheduleBlock, $period, $classdayCount)
    {
        for ($i = 0; $i < $classdayCount; $i++) {
            if (!isset($scheduleBlock[($period)][$i])) {
                return false;
            }
        }
        return true;
    }

    private function getNumbOfConflicts()
    {
        return $this->conflicts;
    }

    public function setFitness($fitness)
    {
        $this->fitness = $fitness;
    }

    public function getFitness()
    {
        return $this->fitness;
    }

    public function calculateFitness()
    {
        $this->conflicts = 0;
        $teacher_schedules = [];
        $conflicting_teachers = [];
        $teacher_section_counts = [];

        foreach ($this->chromosome as $schedule) {
            $section = $schedule['section']->name;
            foreach ($schedule['period'] as $periodIndex => $period) {
                foreach ($period as $classBlock) {
                    $teacher_id = $classBlock['teacher'];
                    $grade_level = $schedule['gradelevel']['level'];

                    if (!isset($teacher_schedules[$grade_level][$teacher_id])) {
                        $teacher_schedules[$grade_level][$teacher_id] = [];
                    }

                    if (!isset($teacher_schedules[$grade_level][$teacher_id][$periodIndex])) {
                        $teacher_schedules[$grade_level][$teacher_id][$periodIndex] = [];
                    }

                    // Check for conflicts
                    foreach ($classBlock['classday'] as $day) {
                        if (in_array($day, $teacher_schedules[$grade_level][$teacher_id][$periodIndex])) {
                            $conflicting_teachers[$grade_level][$day][$teacher_id][] = $section;
                            $this->conflicts++;
                        } else {
                            $teacher_schedules[$grade_level][$teacher_id][$periodIndex][] = $day;
                        }
                    }
                }
            }
        }
        $this->teacher_conflicts = $conflicting_teachers;
        return round((1 / ($this->conflicts + 1)), 6);
    }

        /**
     * Get a printout of the individual
     *
     * @return string Output of the individual details
     */
    public function __toString()
    {
        return $this->getChromosomeString();
    }

    public function getChromosomeString()
    {
        return implode(",", $this->chromosome);
    }

    public function save()
    {
        //save schedule to database
    }
}
