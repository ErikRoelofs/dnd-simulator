<?php

class BaseACCalculation implements ACCalculationInterface
{

    protected $owner;

    /**
     * BaseACCalculation constructor.
     * @param $owner
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }


    public function calculate()
    {
        return 10 + $this->owner->getAbility(Ability::DEXTERITY);
    }

    public function getTags()
    {
        return [
            self::TAG_UNARMORED
        ];
    }

}
