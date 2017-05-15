<?php

// simplified: source is always assumed to be in sight; movement isn't modeled.
class Frightened implements ModifyRollConditionInterface
{

    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
        if($type === CreatureInterface::ROLL_ABILITY) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
    }

}
