<?php

class PassAction implements ActionInterface
{

    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
        return [];
    }

    public function getTargetSlots()
    {
        return [];
    }

    public function predict(Perspective $perspective, $targets)
    {
        return [];
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return true;
    }

    public function getResourceCost()
    {
        return [];
    }

}
