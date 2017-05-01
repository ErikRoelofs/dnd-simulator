<?php

class Incapicitated implements ConditionInterface
{
    public function replaceRoll($type, $data = null)
    {
        return null;
    }

    public function modifiesRoll($type, $data = null)
    {
        return null;
    }

    public function restrictsAvailableActions()
    {
        return [ActionInterface::TYPE_ACTION, ActionInterface::TYPE_BONUS];
    }

}
