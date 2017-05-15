<?php

class AddACCalculation implements StartStopConditionInterface
{

    /**
     * @var ACCalculationInterface
     */
    protected $calculation;

    /**
     * AddACCalculation constructor.
     * @param ACCalculationInterface $calculation
     */
    public function __construct(ACCalculationInterface $calculation)
    {
        $this->calculation = $calculation;
    }

    public function start(CreatureInterface $owner)
    {
        $owner->addACCalculation($this->calculation);
    }

    public function stop(CreatureInterface $owner)
    {
        $owner->removeACCalculation($this->calculation);
    }

}
