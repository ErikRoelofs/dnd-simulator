<?php

class PassMovementAction implements ActionInterface
{

    public function getType()
    {
        return ActionInterface::TYPE_MOVEMENT;
    }

    public function perform(Perspective $perspective, $targets)
    {
        return [];
    }

    public function getTargetSlots()
    {
        return [];
    }

}
