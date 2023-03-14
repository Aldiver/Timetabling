<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\Department;

class Module
{
    /**
     * Id of module
     *
     * @var int
     */
    private $moduleId;

    /**
     * Module's code
     *
     * @var string
     */
    private $moduleModel;

    /**
     * IDs of teachers handling this course
     *
     * @var array
     */
    private $teacherIds;

    /**
     * Number of allocations done for this module so far
     *
     * @var int
     */
    private $allocatedSlots;

    private $nonAdvisory = false;

    /**
     * Create a new module
     *
     * @param int $moduleId ID of module or course
     * @param array  $teacherIds Teacher teaching this module
     */
    public function __construct($moduleId, $teacherIds)
    {
        $this->moduleId = $moduleId;
        $this->moduleModel = Department::find($moduleId);
        if ($this->moduleModel->name === "TLE DEPARTMENT") {
            $this->nonAdvisory = true;
        }
        $this->teacherIds = $teacherIds;
        $this->allocatedSlots = 0;
    }

    /**
     * Get ID of a module
     *
     * @return int ID Of module
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Get the code of the module
     *
     * @return string Code of the module
     */
    public function getModuleCode()
    {
        return $this->moduleModel->subjects()->first()->name;
    }

    /**
     * Get the module name
     *
     * @return string Module name
     */
    public function getName()
    {
        return $this->moduleModel->name;
    }

    /**
     * Get the number of slots this module should take
     *
     * @return int The number of slots
     */
    public function getSlots($groupId)
    {
        return $this->moduleModel->subjects()->first()->hours_per_week; //eto na mismo
    }

    /**
     * Get the slots of this module allocated so far
     *
     * @return int Allocated slots
     */
    public function getAllocatedSlots()
    {
        return $this->allocatedSlots;
    }

    public function resetAllocated()
    {
        $this->allocatedSlots = 0;
    }

    /**
     * Increase the count of slots allocated so far
     *
     * @return void
     */
    public function increaseAllocatedSlots()
    {
        $this->allocatedSlots += 1;
    }

    /**
     * Get a random teacher that can teach this module
     *
     * @return int ID of teacher
     */
    public function getRandomTeacherId()
    {
        $pos = rand(0, count($this->teacherIds) - 1);
        return $this->teacherIds[$pos];
    }

    public function getTeacherIds()
    {
        return $this->teacherIds;
    }

    public function isAdvisory()
    {
        return $this->nonAdvisory;
    }
}
