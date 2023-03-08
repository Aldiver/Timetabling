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
            //init $teacher_loading
            foreach ($currentGradelevel->getModuleIds() as $moduleId) {
                $module = $schoolprogram->getModule($moduleId, $currentGradelevel->getId());
                foreach ($module->getTeacherIds() as $teacherId) {
                    if (!isset($this->teacher_loading[$currentGradelevel->getId()][$moduleId][$teacherId])) {
                        $this->teacher_loading[$currentGradelevel->getId()][$moduleId][$teacherId] = [];
                    }
                }
            }
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
        $timetable->copyTimeslot();
        $timetable->reserveTimeslots();
        foreach ($group->getSectionIds() as $sections) {
            $reserveSubjects = [];
            foreach ($group->getModuleIds() as $moduleId) {
                $module = $timetable->getModule($moduleId, $group->getId());
                if ($group->getId() > 2 && $module->getName() === 'TLE DEPARTMENT') {
                    foreach ($this->teacher_loading[$group->getId()][$moduleId] as $nonAdvisoryTeachers) {
                        $this->teacher_loading[$group->getId()][$moduleId][$nonAdvisoryTeachers][] = 1; //exclude period 1
                    }
                }
                // Add random teacher. Filtered by lowest number of assigned sections/loads
                $teacher = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$moduleId])->random();
                $toExclude = (count($this->teacher_loading[$group->getId()][$moduleId][$teacher]) >= 7) ? [] : $this->teacher_loading[$group->getId()][$moduleId][$teacher];

                $newChromosome[$chromosomeIndex] = [$teacher];

                // Add random time slot
                if ($module->getSlots($group->getId()) == 4) {
                    $timeslotId = $timetable->getRandomGroupedTimeslot($module->getSlots($group->getId()), $toExclude);
                    $this->teacher_loading[$group->getId()][$moduleId][$teacher][] = array_shift($timeslotId);
                    foreach ($timeslotId as $keyId) {
                        $newChromosome[$chromosomeIndex][] = $keyId;
                    }
                } else {
                    // ARPAN and ESP
                    $reserveSubjects[] = $chromosomeIndex;
                    $reserveSubjects[] = $moduleId;
                    // $chromosomeIndex++;
                }
                $chromosomeIndex++;
            }
            //Add reserved subjects brute forced
            $moduleId1 = $reserveSubjects[1];
            $moduleId2 = $reserveSubjects[3];
            $module1 = $timetable->getModule($moduleId1, $group->getId());
            $module2 = $timetable->getModule($moduleId2, $group->getId());
            $teacher1 = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$moduleId1])->random();
            $teacher2 = $this->getFilteredTeachers($this->teacher_loading[$group->getId()][$moduleId2])->random();
            $toExclude1 = (count($this->teacher_loading[$group->getId()][$moduleId1][$teacher1]) >= 7) ? [] : $this->teacher_loading[$group->getId()][$moduleId1][$teacher1];
            $toExclude2 = (count($this->teacher_loading[$group->getId()][$moduleId2][$teacher2]) >= 7) ? [] : $this->teacher_loading[$group->getId()][$moduleId2][$teacher2];
            $timeslotId = $timetable->getRandomGroupedTimeslot(4, array_merge($toExclude1, $toExclude2));
            $this->teacher_loading[$group->getId()][$moduleId1][$teacher1][] = $timeslotId[0];
            $this->teacher_loading[$group->getId()][$moduleId2][$teacher2][] = $timeslotId[0];

            $newChromosome[$reserveSubjects[0]] = [$teacher1, $timeslotId[1], $timeslotId[2]];

            if ($module1->getSlots($group->getId()) == 3) {
                $newChromosome[$reserveSubjects[0]][] = $timetable->getRandomTimeslot()->getId();
                $newChromosome[$reserveSubjects[2]] = [$teacher2, $timeslotId[3], $timeslotId[4]];
            } else {
                $newChromosome[$reserveSubjects[2]] = [$teacher2, $timeslotId[3], $timeslotId[4], $timetable->getRandomTimeslot()->getId()];
            }
            //end
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

    private function getFilteredTeachers($teachersPerModule)
    {
        $teacher_loading = collect($teachersPerModule);

        $minCount = $teacher_loading->min(function ($periods) {
            return count($periods);
        });

        return $teacher_loading->filter(function ($periods) use ($minCount) {
            return count($periods) === $minCount;
        })->keys();
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
        $flatArray = array_merge(...$this->chromosome);
        return implode(",", $flatArray);
    }

    public function save()
    {
        //save schedule to database
    }
}
