<?php

class SlayCreatureGoal implements GoalInterface
{

    /**
     * @var CreatureInterface
     */
    protected $creature;

    /**
     * SlayCreatureGoal constructor.
     * @param CreatureInterface $creature
     */
    public function __construct(CreatureInterface $creature)
    {
        $this->creature = $creature;
    }

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
                if($modification->getTarget() === $this->creature) {
                    $impact += $modification->getDamage();
                }
            }
        }
        return $impact;
    }

}
