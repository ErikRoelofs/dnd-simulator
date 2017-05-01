<?php

class Restrained implements ConditionInterface
{
    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACKED) {
            return CreatureInterface::DIE_ADVANTAGE;
        }
        return null;
    }

    public function restrictsAvailableActions()
    {
        return [];
    }

}
