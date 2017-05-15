<?php

class Incapicitated implements RestrictActionsConditionInterface
{
    public function restrictsAvailableActions()
    {
        return [ActionInterface::TYPE_ACTION, ActionInterface::TYPE_BONUS];
    }

}
