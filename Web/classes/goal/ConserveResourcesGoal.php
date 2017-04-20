<?php

class ConserveResourcesGoal implements GoalInterface
{

    protected $importance;

    /**
     * ConserveResourcesGoal constructor.
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
        // or should this be $perspective->getMe()->getTotalResourceWorth() ? or currentResourceWorth()?
        $impact = 1;
        foreach($action->getResourceCost() as $resource) {
            $impact -= $resource->getValue();
        }
        return $impact;
    }

}
