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
    private $timeslotsCopy;
    private $groupedTimeslotsCopy;

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
                $teacherKey = key($chromosome[$chromosomePos]);
                // dd($chromosome[$chromosomePos]);
                for ($i = 0; $i < $module->getSlots($group->getId()); $i++) {
                    $classes[$classIndex] = new SHSClass($classIndex, $group->getId(), $section, $moduleId);

                    // Add timeslot
                    if (isset($chromosome[$chromosomePos][$teacherKey][$i])) {
                        $classes[$classIndex]->addTimeslot($chromosome[$chromosomePos][$teacherKey][$i]);
                    } else {
                        $classes[$classIndex]->addTimeslot(null);
                    }

                    // Add professor
                    $classes[$classIndex]->addTeacher($teacherKey);
                    $this->groupedClasses[$section][] = $classes[$classIndex];
                    $classIndex++;
                }
                $chromosomePos++;
            }
        }
        $this->groupedClasses = $groupedClasses;
        $this->classes = $classes;
        // dd($classes);
    }

    public function createScheme($gradelevel, $solution)
    {
        $classes = [];
        //trial
        $chromosome = $solution->getChromosome();
        $chromosomePos = 0;
        $classIndex = 0;
        $group = $gradelevel;

        foreach ($group->getSectionIds() as $section) {
            $moduleIds = $group->getModuleIds();
            foreach ($moduleIds as $moduleId) {
                $module = $this->getModule($moduleId, $group->getId());
                $teacherKey = key($chromosome[$chromosomePos]);
                $timeslotIds = array_merge(...$chromosome[$chromosomePos]);
                foreach ($timeslotIds as $timeslotId) {
                    $classes[$group->getLevel()][$section][] = [$this->getTeacher($teacherKey)->getName(), $this->getModule($moduleId, $group->getId())->getModuleCode(), $timeslotId];
                }
                $chromosomePos++;
            }
            usort($classes[$group->getLevel()][$section], function ($a, $b) {
                // extract T and D values from sub-item
                preg_match('/D(\d+)T(\d+)/', $a[2], $a_matches);
                preg_match('/D(\d+)T(\d+)/', $b[2], $b_matches);

                // compare D values
                $d_cmp = strcmp($a_matches[2], $b_matches[2]);
                if ($d_cmp !== 0) {
                    return $d_cmp;
                }

                // compare T values
                return strcmp($a_matches[1], $b_matches[1]);
            });
        }
        return $classes;
    }

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

    public function getGroupedTimeslot($periodId)
    {
        return $this->groupedTimeslot[$periodId];
    }

    public function removedSomeGroupedTimeslots($periodId, $toRemove)
    {
        if (is_array($toRemove)) {
            foreach ($toRemove as $key) {
                unset($this->groupedTimeslot[$periodId][$key]);
            }
        } else {
            unset($this->groupedTimeslot[$periodId][$key]);
        }
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

    public function copyTimeslot()
    {
        $this->timeslotsCopy = $this->timeslots;
        $this->groupedTimeslotsCopy = $this->groupedTimeslots;
    }

    public function reset()
    {
        $this->timeslots = $this->timeslotsCopy;
        $this->groupedTimeslots = $this->groupedTimeslotsCopy;
    }

    public function reserveTimeslots()
    {
        $keys = array_keys($this->groupedTimeslots);
        $filteredKeys = array_diff($keys, [1,7]);
        $randomPeriods = array_rand($filteredKeys, 4);
        $randomNumbers = [];
        while (count($randomNumbers) < 4) {
            $number = mt_rand(0, 4);
            if (!in_array($number, $randomNumbers)) {
                $randomNumbers[] = $number;
            }
        }
        for ($i = 0; $i < 4; $i++) {
            array_splice($this->groupedTimeslots[$filteredKeys[$randomPeriods[$i]]], $randomNumbers[$i], 1);
        }
    }

    public function removeTimeslots($toRemove)
    {
        if (is_array($toRemove)) {
            foreach ($toRemove as $key) {
                unset($this->timeslots[$key]);
            }
        } else {
            unset($this->timeslots[$toRemove]);
        }
    }
    public function removeGroupedTimeslots($period)
    {
        unset($this->groupedTimeslots[$period]);
    }
    /**
     * Get a random time slot
     *
     * @return Timeslot A timeslot
     */
    public function getRandomGroupedTimeslot($count, $excluded, $notAllowed)
    {
        if (count($excluded) >= 7) {
            $excluded = [];
        }
        // $excluded = [];
        $keys = array_keys($this->groupedTimeslots);
        // dd($this->groupedTimeslots, $keys);
        $filteredKeys = array_diff($keys, $excluded);

        if (!$filteredKeys) {
            return false;
        }
        if (!$notAllowed) {
            do {
                $randomPeriod = $filteredKeys[array_rand($filteredKeys)];
                $randomDays = array_rand($this->groupedTimeslots[$randomPeriod], $count);
            } while (count($this->groupedTimeslots[$randomPeriod]) != 4);
        } else {
            do {
                $randomPeriod = $filteredKeys[array_rand($filteredKeys)];
                if ($randomPeriod == 1) {
                    $randomDays = array_rand($this->groupedTimeslots[$randomPeriod], $count);
                    break;
                }
                // print "Period ID " .$randomPeriod. " \n";
                $randomDays = array_rand($this->groupedTimeslots[$randomPeriod], $count);
            } while (count($this->groupedTimeslots[$randomPeriod]) < 5);
        }

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
    public function calcClashes($individual)
    {
        $individual->check();
        return $individual->getClashes();
    }
}
