<?php

class MediumArmorACCalculation extends AbstractACCalculation
{

    public function calculate()
    {
        return $this->armor + min( $this->owner->getAbility(Ability::DEXTERITY), 2 ) + $this->getConditionalBonus();
    }

    public function getTags()
    {
        return [
            self::TAG_MEDIUM_ARMOR
        ];
    }

}
