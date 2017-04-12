<?php

class HealFriendsGoal implements GoalInterface
{

    protected $importance;

    /**
     * HealFirstSmashLaterGoal constructor.
     * @param $importance
     */
    public function __construct($importance)
    {
        $this->importance = $importance;
    }

    /**
     * @return mixed
     */
    public function getImportance()
    {
        return $this->importance;
    }

    public function calculateImpact(Perspective $perspective, ActionInterface $action, $targets)
    {
        $impact = 0;
        $outcomes = $action->predict($perspective, $targets);
        foreach($outcomes as $modification) {
            if($modification instanceof HealDamageModification) {
                $impact += $modification->getAmount();
            }
        }
        return $impact;
    }

}
