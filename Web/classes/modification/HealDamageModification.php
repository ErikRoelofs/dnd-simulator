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

    public function execute(EventDispatcher $dispatcher)
    {
        $realHealed = $this->target->healDamage($this->amount);
        $dispatcher->dispatch(new Event("modification.healDamage", [ 'target' => $this->target, 'amount' => $this->amount]));
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
