<?php

class PassBonusAction implements ActionInterface
{

    public function getType()
    {
        return ActionInterface::TYPE_BONUS;
    }

    public function perform(Perspective $perspective, $targets)
    {
    }

    public function getTargetSlots()
    {
        return [];
    }

}
