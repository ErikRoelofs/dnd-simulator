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
        $painNeeded = $this->painNeeded($perspective);
        if($painNeeded == 0) { return 0; }
        $impact = 0;
        $prediction = $action->predict($perspective, $targets);
        foreach($prediction->getOutcomes() as $outcome) {
            foreach($outcome->getModifications() as $modification) {
                if($modification instanceof TakeDamageModification) {
                    $impact += ($modification->getTarget()->predictDamageTaken($modification->getDamage()) * $outcome->getOdds());
                }
            }
        }
        return $impact / $painNeeded;
    }

    private function painNeeded(Perspective $perspective) {
        $needed = 0;
        foreach($perspective->getOtherFaction()->getCreatures() as $member) {
            $needed += $member->getCurrentHP();
        }
        return $needed;
    }

}
