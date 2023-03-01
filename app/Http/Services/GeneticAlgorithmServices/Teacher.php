<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\Teacher as TeacherModel;

class Teacher
{
    /**
     * ID of Teacher
     *
     * @var int
     */
    private $id;

    /**
     * Teacher model from db
     *
     * @var App\Models\Teacher
     */
    private $TeacherModel;

    /**
     * Create a new Teacher
     *
     * @param int $id ID of Teacher
     * @param array $occupiedSlots Timeslots that the Teacher is not available
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->TeacherModel = TeacherModel::find($this->id);
    }

    /**
     * Get ID Of Teacher
     *
     * @return int ID Of Teacher
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name of Teacher
     *
     * @return string Name of Teacher
     */
    public function getName()
    {
        return $this->TeacherModel->name;
    }

    public function getOccupiedSlots()
    {
        return $this->occupiedSlots;
    }
}
