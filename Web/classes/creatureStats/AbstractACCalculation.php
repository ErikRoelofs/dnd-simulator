<?php

abstract class AbstractACCalculation implements ACCalculationInterface
{
    /**
     * @var CreatureInterface
     */

    protected $owner;

    protected $armor;

    /**
     * BaseACCalculation constructor.
     * @param $owner
     */
    public function __construct(CreatureInterface $owner, $armor)
    {
        $this->owner = $owner;
        $this->armor = $armor;
    }


    abstract public function calculate();

    abstract public function getTags();

    protected function getConditionalBonus() {
        $bonus = 0;
        $conditions = $this->owner->getConditionsModifying(ModifyACConditionInterface::class);
        foreach($conditions as $condition) {
            $bonus += $condition->modifyAmount($this);
        }
        return $bonus;
    }
}
