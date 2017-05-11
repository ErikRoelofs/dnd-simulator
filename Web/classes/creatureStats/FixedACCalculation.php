<?php

class FixedACCalculation implements ACCalculationInterface
{

    protected $amount;

    /**
     * FixedACCalculation constructor.
     * @param $amount
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function calculate()
    {
        return $this->amount;
    }

    public function getTags()
    {
        return [];
    }


}
