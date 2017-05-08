<?php

class Outcome
{

    /**
     * @var ModificationInterface[]
     */
    protected $modifications = [];

    /**
     * @var float
     */
    protected $odds;

    /**
     * Outcome constructor.
     * @param ModificationInterface[] $modifications
     * @param float $odds
     */
    public function __construct(array $modifications, $odds)
    {
        $this->modifications = $modifications;
        $this->odds = $odds;
    }

    /**
     * @return ModificationInterface[]
     */
    public function getModifications()
    {
        return $this->modifications;
    }

    /**
     * @return float
     */
    public function getOdds()
    {
        return $this->odds;
    }

}
