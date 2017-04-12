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
            $targets[] = $this->findTarget($perspective, $slot);
        }

        $todo->perform($perspective, $targets);
    }

    private function getMostDamagingAvailable(ActionPool $actions) {
        return $actions->getActions()[0];
    }

    private function findTarget(Perspective $perspective, $slot) {
        if($slot === ActionInterface::TARGET_ENEMY_CREATURE) {
            return $this->findEnemy($perspective);
        }
        else {
            return $this->findAlly($perspective);
        }
    }

    private function findEnemy(Perspective $perspective) {
        $list = $perspective->getOtherFaction()->getCreatures();
        $lowestHP = INF;
        $target = null;
        foreach($list as $creature) {
            if($creature->getCurrentHP() < $lowestHP) {
                $lowestHP = $creature->getCurrentHP();
                $target = $creature;
            }
        }
        return $target;
    }

    private function findAlly(Perspective $perspective) {
        $list = $perspective->getMyFaction()->getCreatures();
        $lowestHP = INF;
        $target = null;
        foreach($list as $creature) {
            if($creature->getCurrentHP() < $lowestHP) {
                $lowestHP = $creature->getCurrentHP();
                $target = $creature;
            }
        }
        return $target;
    }

}
