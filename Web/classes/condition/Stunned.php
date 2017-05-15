<?php

class Stunned implements RestrictActionsConditionInterface, ModifyRollConditionInterface, ReplaceRollConditionInterface
{
    public function replaceRoll($type, $data = null)
    {
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
