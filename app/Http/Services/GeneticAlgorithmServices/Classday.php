<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\Classday as DayModel;

class Classday
{
    /**
     * ID Of time slot
     *
     * @var int
     */
    private $classdayId;

    /**
     * Model of day from database
     *
     * @var App\Models\Classday;
     */
    private $dayModel;

    /**
     * Create a classday
     *
     * @param $classdayId Id of classday
     */
    public function __construct($classdayId)
    {
        $this->classdayId = $classdayId;
        $this->dayModel = DayModel::find($classdayId);
    }

    /**
     * Get ID of classday
     *
     * @return int Id of classday
     */
    public function getId()
    {
        return $this->classdayId;
    }

    /**
     * Get classday
     *
     * @return int Classday
     */
    public function getClassday()
    {
        return $this->dayModel->short_name;
    }

    /**
     * Get rank of Class day
     *
     * @return int ID of next timeslot
     */
    public function getRank()
    {
        return $this->dayModel->rank;
    }
}
