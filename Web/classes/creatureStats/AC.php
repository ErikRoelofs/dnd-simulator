<?php

class AC
{
    /**
     * @var CreatureInterface
     */
    protected $owner;

    /**
     * @var ACCalculationInterface[]
     */
    protected $calculations;

    /**
     * AC constructor.
     * @param CreatureInterface $owner
     * @param $calculations
     */
    public function __construct(CreatureInterface $owner, $calculations)
    {
        $this->owner = $owner;
        $this->calculations = $calculations;
    }

    /**
     * @return mixed
     */
    public function addCalculation(ACCalculationInterface $calculation)
    {
        $this->calculations[] = $calculation;
    }

    public function removeCalculation(ACCalculationInterface $remove) {
        foreach($this->calculations as $key => $calculation) {
            if($calculation === $remove) {
                unset($this->calculations[$key]);
                return true;
            }
        }
        return false;
    }

    public function getCurrentAC() {
        $max = 0;
        foreach($this->calculations as $calculation) {
            $max = max($max, $calculation->calculate());
        }
        return $max;
    }

}
