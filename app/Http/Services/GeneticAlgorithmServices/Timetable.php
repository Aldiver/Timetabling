<?php

namespace App\Http\Services\GeneticAlgorithmServices;

class Timetable
{
    /**
     * Collection of teachers indexed by their IDs
     *
     * @var array
     */
    private $teachers;

    /**
     * Collection of modules indexed by their IDs
     *
     * @var array
     */
    private $modules;

    /**
     * Collection of class groups indexed by their IDs
     *
     * @var array
     */
    private $groups;

    /**
     * Collection of time slots
     *
     * @var array
     */
    private $timeslots;

    /**
     * Classes
     *
     * @var int
     */
    private $groupedTimeslots;

    /**
     * Number of classes scheduled
     *
     * @var int
     */
    private $numClasses;

    /**
     * Classes
     *
     * @var int
     */
    private $groupedClasses;



    /**
     * Create a new instance of this class
     */
    public function __construct()
    {
        $this->teachers = [];
        $this->modules = [];
        $this->groups = [];
        $this->timeslots = [];
        $this->numClasses = 0;
    }

    /**
     * Get the groups
     *
     * @return array The groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Get the timeslots
     *
     * @return array The timeslots
     */
    public function getTimeslots()
    {
        return $this->timeslots;
    }

    /**
     * Get the timeslots
     *
     * @return array The timeslots
     */
    public function getGroupedTimeslots()
    {
        return $this->groupedTimeslots;
    }

    /**
     * Get the modules
     *
     * @return array The modules
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Get the teachers
     *
     * @return array Collection of teachers
     */
    public function getteachers()
    {
        return $this->teachers;
    }

    /**
     * Add a teacher
     *
     * @param int $teacherId Id of teacher
     */
    public function addTeacher($teacherId)
    {
        $this->teachers[$teacherId] = new Teacher($teacherId);
    }

    /**
     * Add a new module
     *
     * @param int $moduleId Id of module
     * @param array $teacherIds Ids of teachers
     */
    public function addModule($moduleId, $groupId, $teacherIds)
    {
        $this->modules[$moduleId][$groupId] = new Module($moduleId, $teacherIds);
    }

    /**
     * Add a group to this timetable
     *
     * @param int $groupId ID of group
     * @param int $groupSize Size of the group
     * @param array $moduleIds IDs of modules
     */
    public function addGroup($groupId, $moduleIds, $sectionsIds)
    {
        $this->groups[$groupId] = new Group($groupId, $moduleIds, $sectionsIds);
        $this->numClasses = 0;
    }

    /**
     * Add a new timeslot
     *
     * @param int $timeslotId ID of time slot
     * @param string $timeslot Timeslot
     */
    public function addTimeslot($timeslotId)
    {
        $this->timeslots[$timeslotId] = new Timeslot($timeslotId);
    }

    /**
     * Add a new grouped Timeslot
     *
     * @param int $timeslotId ID of time slot
     * @param string $timeslot Timeslot
     */
    public function addGroupedTimeslot($period, $timeslotIds)
    {
        $this->groupedTimeslots[$period] = $timeslotIds;
    }

    /**
     * Create classes using individual's chromosomes
     *
     * @param Individual $individual Individual
     */
    public function createClasses($individual, $currentGradelevel)
    {
        $classes = [];

        $chromosome = $individual->getChromosome();
        $chromosomePos = 0;
        $classIndex = 0;
        $group = $currentGradelevel;
        foreach ($group->getSectionIds() as $section) {
            $moduleIds = $group->getModuleIds();
            $groupedClasses[$group->getId()][$section] = [];
            foreach ($moduleIds as $moduleId) {
                $module = $this->getModule($moduleId, $group->getId());

                $classes[$classIndex] = new SHSClass($classIndex, $group->getId(), $section, $moduleId);
                // Add teacher
                $classes[$classIndex]->addTeacher($chromosome[$chromosomePos][0]);

                for ($i = 1; $i <= $module->getSlots($group->getId()); $i++) {
                    // Add timeslot
                    $classes[$classIndex]->addTimeslot($chromosome[$chromosomePos][$i]);
                }

                $chromosomePos++;
                $groupedClasses[$group->getId()][$section][] =  $classes[$classIndex];
                $classIndex++;
            }
        }
        $this->groupedClasses = $groupedClasses;
        $this->classes = $classes;
        // dd($this->groupedClasses);
    }

    /**
     * Get the string that shows how the timetable chromosome is to be read
     *
     * @return string Chromosome scheme
     */

    /**
     * Get teacher with given ID
     *
     * @param int $teacherId ID of teacher
     */
    public function getTeacher($teacherId)
    {
        return $this->teachers[$teacherId];
    }

    /**
     * Get module by Id
     *
     * @param int $moduleId ID of module
     */
    public function getModule($moduleId, $groupId)
    {
        return $this->modules[$moduleId][$groupId];
    }

    /**
     * Get modules of a student group with given ID
     *
     * @param int $groupId ID of group
     */
    public function getGroupModules($groupId)
    {
        $group = $this->groups[$groupId];
        return $group->getModuleIds();
    }

    /**
     * Get a group using its group ID
     *
     * @param int $groupId ID of group
     * @return Group A group
     */
    public function getGroup($groupId)
    {
        return $this->groups[$groupId];
    }

    /**
     * Get timeslot with given ID
     *
     * @param int $timeslotId ID Of timeslot
     * @return Timeslot A timeslot
     */
    public function getTimeslot($timeslotId)
    {
        return $this->timeslots[$timeslotId];
    }

    /**
     * Get a random time slot
     *
     * @return Timeslot A timeslot
     */
    public function getRandomTimeslot()
    {
        return $this->timeslots[array_rand($this->timeslots)];
    }

    /**
     * Get a random time slot
     *
     * @return Timeslot A timeslot
     */
    public function getRandomGroupedTimeslot($count, $excluded)
    {
        if (count($excluded) == 7) {
            $excluded = [];
        }
        $keys = array_keys($this->groupedTimeslots);
        $filteredKeys = array_diff($keys, $excluded);
        $randomPeriod = $filteredKeys[array_rand($filteredKeys)];
        $randomDays = array_rand($this->groupedTimeslots[$randomPeriod], $count);
        $returnValue = [$randomPeriod];
        foreach ($randomDays as $rkey) {
            $returnValue[] = $this->groupedTimeslots[$randomPeriod][$rkey];
        }
        return $returnValue;
    }

    /**
     * Get a collection of classes
     *
     * @return array Classes
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Get number of classes that need scheduling
     *
     * @return int Number of classes
     */
    public function getNumClasses()
    {
        if ($this->numClasses > 0) {
            return $this->numClasses;
        }

        $numClasses = 0;

        foreach ($this->groups as $group) {
            $numClasses += count($group->getModuleIds());
        }

        $this->numClasses = $numClasses;
        return $numClasses;
    }

    /**
     * Calculate the number of clashes
     *
     * @return $numClashes Number of clashes
     */
    public function calcClashes()
    {
        $clashes = 0;
        $conflict = 0;
        $conflict2 = 0;
        $teacher_loads = [];

        foreach ($this->groupedClasses as $sections) {
            foreach ($sections as $classes) {
                $timeslotIds = [];
                foreach ($classes as $class) {
                    $timeslotIds = $class->getTimeslotId();
                    $timeslotSet = array_flip($timeslotIds);

                    // Check for timeslot clashes within the class
                    if (count($timeslotIds) != count($timeslotSet)) {
                        $clashes++;
                        $conflict2++;
                        continue;
                    }

                    // Check for day clashes within the class
                    $days = array_unique(array_map(function ($id) {
                        return $this->getTimeslot($id)->getDayId();
                    }, $timeslotIds));
                    if (count($days) != count($timeslotIds)) {
                        $clashes++;
                        $conflict2++;
                        continue;
                    }

                    // Check for timeslot clashes with other classes
                    foreach ($timeslotIds as $timeslot) {
                        if (isset($timeslots[$timeslot])) {
                            $clashes++;
                            continue;
                        }
                        $timeslots[$timeslot] = true;
                    }

                    // Update the teacher loads
                    $teacherId = $class->getTeacherId();
                    $gradelevelId = $class->getGroupId();
                    if (!isset($teacher_loads[$gradelevelId][$teacherId])) {
                        $teacher_loads[$gradelevelId][$teacherId] = [];
                    }
                    foreach ($timeslotIds as $timeslot) {
                        if (in_array($timeslot, $teacher_loads[$gradelevelId][$teacherId])) {
                            $clashes++;
                            continue;
                        }
                        $teacher_loads[$gradelevelId][$teacherId][] = $timeslot;
                    }
                }
            }
            // print "Conflicts ". $conflict. " \n". $conflict2."\n";
            // dd($clashes, $this->getTimeslot($class->getTimeslotId()[0]));
            return $clashes;
        }
    }

    /**
         * Determine whether a given set of numbers are
         * consecutive
         */
    public function areConsecutive($numbers)
    {
        sort($numbers);

        $min = $numbers[0];
        $max = $numbers[count($numbers) - 1];

        for ($i = $min; $i <= $max; $i++) {
            if (!in_array($i, $numbers)) {
                return false;
            }
        }

        return true;
    }
}
