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

            $mods = array_merge($mods, $todo->getAction()->perform($perspective, $todo->getTargets()));
            unset($actionsLeft[ $todo->getAction()->getType() ]);
        }
        return $mods;
    }

    private function getMostValuableActionAvailable(Perspective $perspective, ActionPool $actions, $actionsLeft) {

        $availableActions = $actions->getActions();
        $executables = [];
        $value = [];
        foreach ($availableActions as $key => $action) {
            if (!isset($actionsLeft[$action->getType()])) {
                unset($availableActions[$key]);
            }
        }

        foreach($this->goals as $goal) {
            foreach ($availableActions as $action) {
                $targetSets = $this->findAllTargetSets($perspective, $action);
                foreach($targetSets as $targets) {
                    $executable = new ExecutableAction($action, $targets);
                    if(!isset($value[spl_object_hash($executable)])) {
                        $value[spl_object_hash($executable)] = 0;
                    }
                    $value[spl_object_hash($executable)] += $goal->calculateImpact($perspective, $action, $targets) * $goal->getImportance();
                    $executables[] = $executable;
                }
            }
        }
        $high = 0;
        $bestExecutable = null;
        foreach($executables as $executable) {
            if($value[spl_object_hash($executable)] >= $high) {
                $bestExecutable = $executable;
                $high = $value[spl_object_hash($executable)];
            }
        }

        return $bestExecutable;
    }

    private function findAllTargetSets(Perspective $perspective, ActionInterface $action) {
        $sets = [];
        // return the empty set if this action has no slots; it can still be performed.
        if($action->getTargetSlots() === []) {
            return $sets = [[]];
        }
        foreach($action->getTargetSlots() as $slot) {
            foreach( $this->findAllTargets($perspective, $slot) as $target ) {
                $sets[] = [ $target ];
            }
        }
        return $sets;
    }

    private function findAllTargets(Perspective $perspective, $slot) {
        if($slot === ActionInterface::TARGET_ENEMY_CREATURE) {
            return $perspective->getOtherFaction()->getCreatures();
        }
        else {
            return $perspective->getMyFaction()->getCreatures();
        }
    }

}
