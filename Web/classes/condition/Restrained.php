<?php

class Restrained implements ConditionInterface
{
    public function replaceRoll($type, $data = null)
    {
        return null;
    }


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
