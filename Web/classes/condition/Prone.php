<?php

// simplified: distance isn't modeled, so attacks aren't modified
class Prone implements ModifyRollConditionInterface
{

    public function modifiesRoll($type, $data = null)
    {
        if($type === CreatureInterface::ROLL_ATTACK) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
    }

}
