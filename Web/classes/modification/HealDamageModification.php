<?php

class HealDamageModification implements ModificationInterface
{

    const EVENT_HEAL_DAMAGE = "modification.healDamage";

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
        $dispatcher->dispatch(new Event(self::EVENT_HEAL_DAMAGE, [ 'target' => $this->target, 'amount' => $this->amount]));
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
