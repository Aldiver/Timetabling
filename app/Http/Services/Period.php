<?php

namespace App\Http\Services;

class Period
{
    public $subject;
    public $classday;
    public $teacher;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    public function setClass($classday)
    {
        $this->classday = $classday;
    }

    public function toArray()
    {
        return [
            'subject' => $this->subject,
            'classday' => $this->classday,
            'teacher' => $this->teacher,
        ];
    }
}
