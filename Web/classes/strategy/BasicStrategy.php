<?php

class BasicStrategy implements StrategyInterface
{

    protected $goals = [];

    /**
     * BasicStrategy constructor.
     * @param array $goals
     */
    public function __construct(array $goals)
    {
        $this->goals = $goals;
    }


    public function doTurn(Perspective $perspective)
    {
        $mods = [];
        $actionsLeft = [ ActionInterface::TYPE_ACTION => true, ActionInterface::TYPE_MOVEMENT => true, ActionInterface::TYPE_BONUS => true];
        while(count($actionsLeft)) {
            $actions = $perspective->getMe()->getActions();
            $todo = $this->getMostValuableActionAvailable($perspective, $actions, $actionsLeft);
            if(!$todo) {
                throw new Exception("No actions :( You didn't add pass-actions to the pool");
            }
            $targets = $this->findTargets($perspective, $todo);

            $mods = array_merge($mods, $todo->perform($perspective, $targets));
            unset($actionsLeft[ $todo->getType() ]);
        }
        return $mods;
    }

    private function getMostValuableActionAvailable(Perspective $perspective, ActionPool $actions, $actionsLeft) {

        $availableActions = $actions->getActions();
        foreach ($availableActions as $key => $action) {
            if (!isset($actionsLeft[$action->getType()])) {
                unset($availableActions[$key]);
            }
        }

        $high = 0;
        $bestAction = null;
        foreach($this->goals as $goal) {
            foreach ($availableActions as $action) {
                $targets = $this->findTargets($perspective, $action);
                $impact = $goal->calculateImpact($perspective, $action, $targets) * $goal->getImportance();
                if ($impact >= $high) {
                    $bestAction = $action;
                    $high = $impact;
                }
            }
        }
        return $bestAction;
    }

    private function findTargets(Perspective $perspective, ActionInterface $action) {
        $slots = $action->getTargetSlots();
        $targets = [];
        foreach ($slots as $slot) {
            $targets[] = $this->findTarget($perspective, $slot);
        }
        return $targets;
    }

    private function findTarget(Perspective $perspective, $slot) {
        if($slot === ActionInterface::TARGET_ENEMY_CREATURE) {
            return (new FindWeakestEnemy())->findTarget($perspective);
        }
        else {
            return (new FindMostWoundedFriendly())->findTarget($perspective);
        }
    }

}
