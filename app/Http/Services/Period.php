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

    public function serialize()
    {
        return serialize([
            $this->subject,
            $this->classday,
            $this->teacher,
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->subject,
            $this->classday,
            $this->teacher,
        ) = unserialize($data);
    }
}
