<?php

class ClassData
{
    private $id;
    private $gradelevel;
    private $section;
    private $subject;
    private $classes;
    private $period;
    private $classdays;
    private $teacher;


    public function __construct($id, $gradelevel, $section)
    {
        $this->id = $id;
        $this->gradelevel = $gradelevel;
        $this->section = $section;
    }

    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
    }

    public function setClassdays($classdays)
    {
        $this->classdays = $classdays;
    }
}
