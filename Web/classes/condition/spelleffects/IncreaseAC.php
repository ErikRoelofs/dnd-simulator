<?php

class IncreaseAC implements ModifyACConditionInterface
{

    protected $amount;

    /**
     * IncreaseAC constructor.
     * @param $amount
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function modifyAmount(ACCalculationInterface $ac)
    {
        return $this->amount;
    }

}
