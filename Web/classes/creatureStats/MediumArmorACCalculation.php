<?php

class MediumArmorACCalculation implements ACCalculationInterface
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
        return $this->armor + min( $this->owner->getAbility(Ability::DEXTERITY), 2 );
    }

    public function getTags()
    {
        return [
            self::TAG_MEDIUM_ARMOR
        ];
    }

}
