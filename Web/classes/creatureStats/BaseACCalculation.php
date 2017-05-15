<?php

class BaseACCalculation extends AbstractACCalculation
{


    /**
     * BaseACCalculation constructor.
     */
    public function __construct(CreatureInterface $owner)
    {
        parent::__construct($owner, 10);
    }

    public function calculate()
    {
        return 10 + $this->owner->getAbility(Ability::DEXTERITY) + $this->getConditionalBonus();
    }

    public function getTags()
    {
        return [
            self::TAG_UNARMORED
        ];
    }

}
