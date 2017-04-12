<?php

class SlayCreatureGoal implements GoalInterface
{

    protected $importance;

    /**
     * @var CreatureInterface
     */
    protected $creature;

    /**
     * SlayCreatureGoal constructor.
     * @param CreatureInterface $creature
     * @param $importance
     */
    public function __construct(CreatureInterface $creature, $importance)
    {
        $this->creature = $creature;
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
                if($modification->getTarget() === $this->creature) {
                    $impact += $modification->getDamage() / $this->creature->getCurrentHP();
                }
            }
        }
        return min(1, $impact);
    }

}
