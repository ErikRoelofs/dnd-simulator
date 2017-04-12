<?php

class PassAction implements ActionInterface
{

    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
    }

    public function getTargetSlots()
    {
        return [];
    }

}
