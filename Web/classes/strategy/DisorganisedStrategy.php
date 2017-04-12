<?php

class DisorganisedStrategy implements StrategyInterface
{
    public function doTurn(Perspective $perspective)
    {
        $actions = $perspective->getMe()->getActions();
        $todo = $this->getMostDamagingAvailable($actions);

        $slots = $todo->getTargetSlots();
        $targets = [];
        foreach($slots as $slot) {
            $targets[] = $this->findTarget($perspective, $slot);
        }

        $todo->perform($perspective, $targets);
    }

    private function getMostDamagingAvailable(ActionPool $actions) {
        return $actions->getActions()[0];
    }

    private function findTarget(Perspective $perspective, $slot) {
        return $perspective->getOtherFaction()->getRandomCreature();
    }

}
