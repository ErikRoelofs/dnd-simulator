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
        $healNeeded = $this->healNeeded($perspective);
        if($healNeeded === 0) { return 0; }
        $impact = 0;
        $outcomes = $action->predict($perspective, $targets);
        // buggy: does not take into account one heal spell hitting & overhealing the same target twice
        foreach($outcomes as $modification) {
            if($modification instanceof HealDamageModification) {
                $impact += min( $modification->getAmount(), $modification->getTarget()->getMaxHP() - $modification->getTarget()->getCurrentHP() );
            }
        }
        return $impact / $healNeeded;
    }

    private function healNeeded(Perspective $perspective) {
        $needed = 0;
        foreach($perspective->getMyFaction()->getCreatures() as $member) {
            $needed += $member->getMaxHP() - $member->getCurrentHP();
        }
        return $needed;
    }
}
