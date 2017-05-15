<?php

class Restrained implements ModifyRollConditionInterface
{

    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
        if($type === CreatureInterface::ROLL_ATTACKED) {
            return CreatureInterface::DIE_ADVANTAGE;
        }
        if($this === CreatureInterface::ROLL_SAVE && $data === Ability::DEXTERITY) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
        return null;
    }

}
