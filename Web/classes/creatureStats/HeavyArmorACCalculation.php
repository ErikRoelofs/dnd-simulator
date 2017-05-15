<?php

class HeavyArmorACCalculation extends AbstractACCalculation
{

    public function calculate()
    {
        return $this->armor + $this->getConditionalBonus();
    }

    public function getTags()
    {
        return [
            self::TAG_HEAVY_ARMOR
        ];
    }

}
