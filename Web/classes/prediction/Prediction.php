<?php

class Prediction
{

    /**
     * @var Outcome[]
     */
    protected $outcomes = [];

    /**
     * Prediction constructor.
     * @param array $outcomes
     */
    public function __construct(array $outcomes = [])
    {
        $this->outcomes = $outcomes;
    }

    /**
     * @return Outcome[]
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }

}
