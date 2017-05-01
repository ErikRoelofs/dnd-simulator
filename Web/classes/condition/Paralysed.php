<?php

class Paralysed implements ConditionInterface
{

    public function replaceRoll($type, $data = null) {
        if($type === CreatureInterface::ROLL_ATTACKED) {
            return 20;
        }
        if($type === CreatureInterface::ROLL_SAVE && ($data === Ability::DEXTERITY || $data === Ability::STRENGTH)) {
            return 1;
        }
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
        return [ActionInterface::TYPE_ACTION, ActionInterface::TYPE_BONUS];
    }

}
