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

            $action = $todo->getAction();
            foreach($action->getResourceCost() as $cost) {
                $cost->spend($action, $perspective->getMe());
            }
            $mods = array_merge($mods, $action->perform($perspective, $todo->getTargets()));
            unset($actionsLeft[ $action->getType() ]);
        }
        return $mods;
    }

    private function getMostValuableActionAvailable(Perspective $perspective, ActionPool $actions, $actionsLeft) {

        $availableActions = $actions->getActions();
        $executables = [];
        $value = [];
        foreach ($availableActions as $key => $action) {
            if (!isset($actionsLeft[$action->getType()]) || !$action->isAvailable($perspective->getMe())) {
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
        // return the empty set if this action has no slots; it can still be performed.
        if($action->getTargetSlots() === []) {
            return [[]];
        }
        // we assume all slot types are equal, for now.
        foreach($action->getTargetSlots() as $slot) {
            $targets = $this->findAllTargets($perspective, $slot);
            $uniques = false;
            if($slot === ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE || $slot === ActionInterface::TARGET_UNIQUE_FRIENDLY_CREATURE) {
                $uniques = true;
            }
            break;
        }

        $set = new Set;
        $count = count($action->getTargetSlots());
        if($uniques) {
            $this->ucombinations($targets, min($count, count($targets)), $set);
        }
        else {
            $this->combinations($targets, $count, $set);
        }

        return $set->items;

    }

    private function findAllTargets(Perspective $perspective, $slot) {
        if($slot === ActionInterface::TARGET_ENEMY_CREATURE || $slot === ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE) {
            return $perspective->getOtherFaction()->getCreatures();
        }
        elseif ($slot === ActionInterface::TARGET_ME) {
            return [$perspective->getMe()];
        }
        else {
            return $perspective->getMyFaction()->getCreatures();
        }
    }

    private function combinations($array, $count, Set $set) {
        if($count == 0) { return; }
        $newSets = [];
        foreach($array as $item) {
            foreach($set->items as $setItem) {
                $copy = $setItem;
                $copy[] = $item;
                $newSets[] = $copy;
            }
        }
        $set->items = $newSets;
        $this->combinations($array, $count - 1, $set);
    }

    private function ucombinations($array, $count, Set $set) {
        if($count == 0) { return; }
        $newSets = [];
        foreach($array as $item) {
            foreach($set->items as $setItem) {
                if(!in_array($item, $setItem)){
                    $copy = $setItem;
                    $copy[] = $item;
                    $newSets[] = $copy;
                }
            }
        }
        $set->items = $newSets;
        $this->ucombinations($array, $count - 1, $set);
    }

}

class Set {
    public $items = [[]];
}
