<?php

// simplified: source is always assumed to be in sight; movement isn't modeled.
class Frightened implements ConditionInterface
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
        if($type === CreatureInterface::ROLL_ABILITY) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
    }

    public function restrictsAvailableActions()
    {
        return [];
    }

}
