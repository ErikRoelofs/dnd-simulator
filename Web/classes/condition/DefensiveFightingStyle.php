<?php

class DefensiveFightingStyle implements ModifyACConditionInterface
{
    public function modifyAmount(ACCalculationInterface $ac)
    {
        foreach($ac->getTags() as $tag) {
            if(in_array($tag, [ACCalculationInterface::TAG_LIGHT_ARMOR, ACCalculationInterface::TAG_MEDIUM_ARMOR, ACCalculationInterface::TAG_HEAVY_ARMOR])) {
                return 1;
            }
        }
        return 0;
    }

}
