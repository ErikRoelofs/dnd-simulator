<?php

interface GoalInterface
{

    public function getImportance();

    public function calculateImpact(Perspective $perspective, ActionInterface $action, $targets);

}
