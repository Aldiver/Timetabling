<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\Period as TimeslotModel;
use App\Models\Classday as DayModel;

class Timeslot
{
    /**
     * ID Of time slot
     *
     * @var int
     */
    private $timeslotId;

    /**
     * Model of day from database
     *
     * @var App\Models\Classday;
     */
    private $dayModel;

    /**
     * Model of timeslot from database
     *
     * @var App\Models\Peroiod;
     */
    private $timeslotModel;


    /**
     * Create a timeslot
     *
     * @param $timeslotId Id of timeslot
     * @param $nextSlot Id of next time slot
     */
    public function __construct($timeslotId)
    {
        $this->timeslotId = $timeslotId;
        // $this->nextSlot = $nextSlot;

        $matches = [];
        preg_match('/D(\d*)T(\d*)/', $timeslotId, $matches);

        $dayId = $matches[1];
        $timeslotId = $matches[2];

        $this->dayModel = DayModel::find($dayId);
        $this->timeslotModel = TimeslotModel::where('rank', $timeslotId)->first();
    }

    /**
     * Get ID of timeslot
     *
     * @return int Id of timeslot
     */
    public function getId()
    {
        return $this->timeslotId;
    }

    /**
     * Get timeslot
     *
     * @return int Timeslot
     */
    public function getTimeslot()
    {
        return $this->dayModel->short_name . ' ' . $this->timeslotModel->rank;
    }

    /**
     * Get the id of time slot after this
     *
     * @return int ID of next timeslot
     */
    public function getNext()
    {
        return $this->nextSlot;
    }

    public function getDayId()
    {
        return $this->dayModel->id;
    }

    public function getTimeslotId()
    {
        return $this->timeslotModel->id;
    }

    public function reserveTimeSlots()
    {
        //
    }

    public function resetReservedTimeSlots()
    {
        //
    }
}
