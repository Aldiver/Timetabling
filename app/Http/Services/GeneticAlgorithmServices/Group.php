<?php

namespace App\Http\Services\GeneticAlgorithmServices;

use App\Models\Gradelevel as GradelevelModel;

class Group
{
    /**
     * ID of group
     *
     * @var int
     */
    private $id;

    /**
     * College class model
     *
     * @var App\Models\Gradelevel
     */
    private $model;

    /**
     * IDs of modules taken by this group
     *
     * @var array
     */
    private $moduleIds;

    /**
     * IDs of sections taken by this group
     *
     * @var array
     */
    private $sectionIds;

    /**
     * Instantiate a new group
     *
     * @param int $id Id of group
     * @param array Ids of modules taken by this group
     */
    public function __construct($id, $moduleIds, $sectionIds)
    {
        $this->id = $id;
        $this->model = GradelevelModel::find($id);
        $this->moduleIds = $moduleIds;
        $this->sectionIds = $sectionIds;
    }

    /**
     * Get the Id of the group
     *
     * @return int Id of group
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the number of sections
     *
     * @return int Size of group
     */
    public function getSize()
    {
        return count($this->sectionIds);
    }

    /**
     * Get the IDs of modules this group is taking
     *
     * @return array Module Ids
     */
    public function getModuleIds()
    {
        return $this->moduleIds;
    }

    /**
     * Get the IDs of sections this group is taking
     *
     * @return array Section Ids
     */
    public function getSectionIds()
    {
        return $this->sectionIds;
    }
}
