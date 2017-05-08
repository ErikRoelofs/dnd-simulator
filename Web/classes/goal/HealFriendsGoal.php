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
        $healNeeded = $this->healNeeded($targets);
        if($healNeeded === 0) { return 0; }
        $impact = 0;

        $prediction = $action->predict($perspective, $targets);
        foreach($prediction->getOutcomes() as $outcome) {
            foreach($outcome->getModifications() as $modification) {
                if($modification instanceof HealDamageModification) {
                    $impact += min( $modification->getAmount(), $modification->getTarget()->getMaxHP() - $modification->getTarget()->getCurrentHP() );
                }
            }
        }
        return $impact / $healNeeded;
    }

    private function healNeeded(array $targets) {
        $needed = 0;
        foreach($targets as $member) {
            $needed += $member->getMaxHP() - $member->getCurrentHP();
        }
        return $needed;
    }
}
