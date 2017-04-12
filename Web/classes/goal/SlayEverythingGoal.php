<?php

class SlayEverythingGoal implements GoalInterface
{

    public function getImportance()
    {
        return 1;
    }

    public function calculateImpact(Perspective $perspective, ActionInterface $action, $targets)
    {
        $impact = 0;
        $outcomes = $action->predict($perspective, $targets);
        foreach($outcomes as $modification) {
            if($modification instanceof TakeDamageModification) {
                $impact += $modification->getDamage();
            }
        }
        return $impact;
    }

}
