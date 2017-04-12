<?php

class HealFirstSmashLaterGoal implements GoalInterface
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
            if($modification instanceof HealDamageModification) {
                $impact += 1000 * $modification->getAmount();
            }
        }
        return $impact;
    }

}
