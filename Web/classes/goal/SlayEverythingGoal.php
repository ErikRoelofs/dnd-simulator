<?php

class SlayEverythingGoal implements GoalInterface
{

    protected $importance;

    public function __construct($importance)
    {
        $this->importance = $importance;
    }

    public function getImportance()
    {
        return $this->importance;
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
