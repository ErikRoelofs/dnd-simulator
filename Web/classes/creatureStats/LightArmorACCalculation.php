<?php

class LightArmorACCalculation extends AbstractACCalculation
{

    public function calculate()
    {
        return $this->armor + $this->owner->getAbility(Ability::DEXTERITY) + $this->getConditionalBonus();
    }

    public function getTags()
    {
        return [
            self::TAG_LIGHT_ARMOR
        ];
    }

}
