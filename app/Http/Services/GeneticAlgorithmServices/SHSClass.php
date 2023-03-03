<?php

namespace App\Http\Services\GeneticAlgorithmServices;

class SHSClass
{
    /**
     * Id of class
     *
     * @var int
     */
    private $id;

    /**
     * Id of group
     *
     * @var int
     */
    private $groupId;
    /**
     * Id of section
     *
     * @var int
     */
    private $sectionId;

    /**
     * ID of module taken by this class
     *
     * @var int
     */
    private $moduleId;

    /**
     * ID of teacher taking this class
     *
     * @var int ID of teacher
     */
    private $teacherId;

    /**
     * Id of timeslot this class is scheduled
     *
     * @var int
     */
    private $timeslotId;

    /**
     * Create a new college class
     *
     * @param int $id Id of class group taking this class
     * @param int $groupId ID of group
     * @param int $moduleId ID of module taken by this class
     */
    public function __construct($id, $groupId, $sectionId, $moduleId)
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->sectionId = $sectionId;
        $this->moduleId = $moduleId;
    }

    /**
     * Set a teacher for this class
     *
     * @param int $teacherId Id of teacher
     */
    public function addTeacher($teacherId)
    {
        $this->teacherId = $teacherId;
    }

    /**
     * Allocate a timeslot for this class
     *
     * @param int ID of timeslot
     */
    public function addTimeSlot($timeslotId)
    {
        $this->timeslotId[] = $timeslotId;
    }

    /**
     * Get the ID of this college class
     *
     * @return int ID of this class
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the id of group taking this class
     *
     * @return int ID of group taking this class
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Get the id of section
     *
     * @return int ID of group
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Get the ID of module treated in this class
     *
     * @return int  ID Of module
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Get the id of teacher taking this class
     *
     * @return int ID of teacher
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }

    /**
     * Get the time slot allocated to this class
     *
     * @return int Time slot ID
     */
    public function getTimeslotId()
    {
        return $this->timeslotId;
    }

    /**
     * Get the time slot count
     *
     * @return int Time slot count
     */
    public function getTimeslotCount()
    {
        return count($this->timeslotId);
    }
}
