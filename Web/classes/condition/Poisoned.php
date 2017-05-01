<?php

class Poisoned implements ConditionInterface
{
    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK || $type === CreatureInterface::ROLL_ABILITY) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
        return null;
    }

    public function restrictsAvailableActions()
    {
        return [];
    }

}
