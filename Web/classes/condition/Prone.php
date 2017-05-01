<?php

// simplified: distance isn't modeled, so attacks aren't modified
class Prone implements ConditionInterface
{
    public function replaceRoll($type, $data = null)
    {
        return null;
    }

    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
    }

    public function restrictsAvailableActions()
    {
        return [];
    }

}
