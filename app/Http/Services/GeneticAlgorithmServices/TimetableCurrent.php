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
     * Collection of class days
     *
     * @var array
     */
    private $classdays;

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
    public function getTeachers()
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
     * Add a new classday
     *
     * @param int $classDayId ID of class day
     * @param string $timeslot Timeslot
     */
    public function addClassday($classDayId)
    {
        $this->classdays[$classDayId] = new Classday($classDayId);
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
            foreach ($moduleIds as $moduleId) {
                $module = $this->getModule($moduleId, $group->getId());

                for ($i = 1; $i <= $module->getSlots($group->getId()); $i++) {
                    $classes[$classIndex] = new SHSClass($classIndex, $group->getId(), $section, $moduleId);

                    // Add timeslot
                    $classes[$classIndex]->addTimeslot($chromosome[$chromosomePos]);
                    $chromosomePos++;

                    // Add professor
                    $classes[$classIndex]->addTeacher($chromosome[$chromosomePos]);
                    $chromosomePos++;

                    $this->groupedClasses[$section][] = $classes[$classIndex];
                    $classIndex++;
                }
            }
        }
        // dd($this->groupedClasses);
        $this->classes = $classes;
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
    public function getRandomGroupedTimeslot($count)
    {
        $randomPeriod = array_rand($this->groupedTimeslots);
        $randomDays = array_rand($this->groupedTimeslots[$randomPeriod], $count);
        $returnValue = [];
        foreach (collect($randomDays) as $key) {
            $returnValue[] = $this->groupedTimeslots[$randomPeriod][$key];
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
     * Get classes scheduled for a given day for a given group
     *
     * @param $dayId ID of day we are getting classes for
     * @param $groupId The ID of the group
     */
    public function getClassesByDay($dayId, $groupId)
    {
        $classes = [];
        $days = [1,2,3,4,5];

        foreach ($this->classes as $class) {
            $timeslot = $this->getTimeslot($class->getTimeslotId());

            $classDayId = $timeslot->getDayId();

            if ($dayId == $classDayId && $class->getGroupId() == $groupId) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    /**
     * Calculate the number of clashes
     *
     * @return $numClashes Number of clashes
     */
    public function calcClashes()
    {
        $clashes = 0;
        $days = $this->classdays;
        foreach ($this->classes as $id => $classA) {
            //check if subjects have been assigned with the same timeslot
            foreach ($this->classes as $id => $classB) {
                if ($classA->getId() != $classB->getId()) {
                    if (($classA->getSectionId() != $classB->getSectionId()) && ($classA->getTeacherId() == $classB->getTeacherId()) && ($classA->getTimeslotId() == $classB->getTimeslotId())) {
                        $clashes++;
                        break;
                    }
                }
            }
        }

        foreach ($this->groupedClasses as $section) {
            foreach ($section as $classA) {
                //check if there are conflicts in schedules between modules
                foreach ($section as $classB) {
                    if ($classA->getId() != $classB->getId()) {
                        if (($classA->getModuleId() != $classB->getModuleId()) && ($classA->getTimeslotId() == $classB->getTimeslotId())) {
                            $clashes++;
                            break;
                        }
                    }
                }

                foreach ($section as $classB) {
                    //teacher must be the same per module
                    if ($classA->getId() != $classB->getId()) {
                        if (($classA->getModuleId() == $classB->getModuleId()) && ($classA->getTeacherId() != $classB->getTeacherId())) {
                            $clashes++;
                            break;
                        }
                    }
                }
                //modules must always have different classday
                foreach ($section as $classB) {
                    if ($classA->getId() != $classB->getId()) {
                        if (($classA->getModuleId() == $classB->getModuleId()) && ($this->getTimeslot($classA->getTimeslotId())->getDayId() == $this->getTimeslot($classB->getTimeslotId())->getDayId())) {
                            $clashes++;
                            break;
                        }
                    }
                }
                //modules must have the same period unless it's ARPAN/ESP
                if ($classA->getModuleId() != 6 || $classA->getModuleId() != 8) {
                    foreach ($section as $classB) {
                        if ($classA->getId() != $classB->getId()) {
                            if (($classA->getModuleId() == $classB->getModuleId()) && ($this->getTimeslot($classA->getTimeslotId())->getTimeslotId() == $this->getTimeslot($classB->getTimeslotId())->getTimeslotId())) {
                                $clashes++;
                                break;
                            }
                        }
                    }
                }
            }
        }

        // 345 => App\Http\Services\GeneticAlgorithmServices\SHSClass^ {#2348
        //     -id: 345
        //     -groupId: 1
        //     -sectionId: 12
        //     -moduleId: 7
        //     -teacherId: 3
        //     -timeslotId: "D2T5"
        //   }

        return $clashes;
    }
    public function getClassesBySection($sectionId)
    {
        $classes = [];

        foreach ($this->classes as $class) {
            $bySection = $this->getGroup($groupId)->getSectionId($class->getSectionId());

            if ($bySection == $sectionId) {
                $classes[] = $class;
            }
        }

        return $classes;
    }
    public function classdaysAreUnique($numbers)
    {
        return true;
    }

    public function regLoadCheckPeriod($numbers)
    {
        return true;
    }
}
