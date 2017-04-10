<?php

class BrutalStrategy implements StrategyInterface
{
    public function doTurn(Perspective $perspective)
    {
        $actions = $perspective->getMe()->getActions();
        $todo = $this->getMostDamagingAvailable($actions);

        $slots = $todo->getTargetSlots();
        $targets = [];
        foreach($slots as $slot) {
            $targets[] = $this->findValidTarget($perspective, $slot);
        }

        $todo->perform($perspective, $targets);
    }

    private function getMostDamagingAvailable(ActionPool $actions) {
        return $actions->getActions()[0];
    }

    private function findValidTarget(Perspective $perspective, $slot) {
        return $perspective->getOtherFaction()->getRandomCreature();
    }

}
