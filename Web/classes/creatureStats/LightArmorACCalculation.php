<?php

class LightArmorACCalculation implements ACCalculationInterface
{

    /**
     * @var CreatureInterface
     */

    protected $owner;

    protected $armor;

    /**
     * BaseACCalculation constructor.
     * @param $owner
     */
    public function __construct(CreatureInterface $owner, $armor)
    {
        $this->owner = $owner;
        $this->armor = $armor;
    }


    public function calculate()
    {
        return $this->armor + $this->owner->getAbility(Ability::DEXTERITY);
    }

    public function getTags()
    {
        return [
            self::TAG_LIGHT_ARMOR
        ];
    }

}
