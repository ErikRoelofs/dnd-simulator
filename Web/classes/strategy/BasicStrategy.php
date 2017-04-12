<?php

class BasicStrategy implements StrategyInterface
{

    public function doTurn(Perspective $perspective)
    {
        $actionsLeft = [ ActionInterface::TYPE_ACTION => true, ActionInterface::TYPE_MOVEMENT => true, ActionInterface::TYPE_BONUS => true];
        while(count($actionsLeft)) {
            $actions = $perspective->getMe()->getActions();
            $todo = $this->getMostValuableActionAvailable($actions, $actionsLeft);
            if(!$todo) {
                throw new Exception("No actions :( You didn't add pass-actions to the pool");
            }

            $slots = $todo->getTargetSlots();
            $targets = [];
            foreach ($slots as $slot) {
                $targets[] = $this->findTarget($perspective, $slot);
            }

            $todo->perform($perspective, $targets);
            unset($actionsLeft[ $todo->getType() ]);
        }
    }

    private function getMostValuableActionAvailable(ActionPool $actions, $actionsLeft) {
        $availableActions = $actions->getActions();
        foreach($availableActions as $key => $action) {
            if(!isset($actionsLeft[$action->getType()])) {
                unset($availableActions[$key]);
            }
        }

        foreach($availableActions as $action) {
            return $action;
        }
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
