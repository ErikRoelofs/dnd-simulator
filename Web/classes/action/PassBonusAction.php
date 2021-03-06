<?php

class PassBonusAction implements ActionInterface
{

    public function getType()
    {
        return ActionInterface::TYPE_BONUS;
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
        return new Prediction();
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
