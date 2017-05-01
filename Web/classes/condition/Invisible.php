<?php

class Invisible implements ConditionInterface
{
    public function replaceRoll($type, $data = null)
    {
        return null;
    }

    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK) {
            return CreatureInterface::DIE_ADVANTAGE;
        }
        if($type === CreatureInterface::ROLL_ATTACKED) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
    }

    public function restrictsAvailableActions()
    {
        return [];
    }

}
