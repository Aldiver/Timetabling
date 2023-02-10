<?php

namespace App\Http\Services;

class Subject
{
    public $subject;
    public $class;
    public $teacher;

    public function __construct($subject, $teacher)
    {
        $this->subject = $subject;
        $this->teacher = $teacher;
    }

    public function setClass($period, $classday)
    {
        $this->class = [];

        for ($i = 0; $i < count($classday); $i++) {
            $this->class[] = [
                "period" => $period[$i],
                "classday" => $classday[$i]
            ];
        }
    }

    public function toArray()
    {
        return [
            'subject' => $this->subject,
            'class' => $this->class,
            'teacher' => $this->teacher,
        ];
    }

    public function serialize()
    {
        return serialize([
            $this->subject,
            $this->class,
            $this->teacher,
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->subject,
            $this->class,
            $this->teacher,
        ) = unserialize($data);
    }
}
