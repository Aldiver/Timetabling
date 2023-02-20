<?php

namespace App\Http\Services;

class ClassData implements \Serializable
{
    private $id;
    private $gradelevel;
    private $section;
    private $periods = [];

    public function __construct($id, $gradelevel, $section)
    {
        $this->id = $id;
        $this->gradelevel = $gradelevel;
        $this->section = $section;
    }

    public function addPeriod($period)
    {
        $this->periods[] = $period;
    }
    public function insertPeriod($periodIndex, $period)
    {
        array_push($this->periods[$periodIndex], $period);
        // $this->periods[] = $period;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'gradelevel' => $this->gradelevel,
            'section' => $this->section,
            'period' => $this->periods
        ];
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->gradelevel,
            $this->section,
            $this->periods
        ]);
    }

    public function unserialize($data)
    {
        list(
            $this->id,
            $this->gradelevel,
            $this->section,
            $this->periods
        ) = unserialize($data);
    }
}
