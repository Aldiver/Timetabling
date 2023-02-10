<?php

namespace App\Http\Services;

class ClassData implements \Serializable
{
    private $id;
    private $gradelevel;
    private $section;
    private $subjects = [];

    public function __construct($id, $gradelevel, $section)
    {
        $this->id = $id;
        $this->gradelevel = $gradelevel;
        $this->section = $section;
    }

    public function addSubject($subject)
    {
        $this->subjects[] = $subject;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'gradelevel' => $this->gradelevel,
            'section' => $this->section,
            'subject' => $this->subjects
        ];
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->gradelevel,
            $this->section,
            $this->subjects
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->id,
            $this->gradelevel,
            $this->section,
            $this->subjects
        ) = unserialize($data);
    }
}
