<?php

class HealDamageModification implements ModificationInterface
{

    /**
     * @var CreatureInterface
     */
    protected $target;

    protected $amount;

    /**
     * TakeDamageModification constructor.
     * @param CreatureInterface $target
     * @param $damage
     */
    public function __construct(CreatureInterface $target, $amount)
    {
        $this->target = $target;
        $this->amount = $amount;
    }

    public function execute(Log $log)
    {
        $realHealed = $this->target->healDamage($this->amount);
        $log->write($this->target->getName() . " has healed for " . $realHealed . " points of damage.", Log::MEDIUM_IMPORTANT);
    }

    /**
     * @return CreatureInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

}
