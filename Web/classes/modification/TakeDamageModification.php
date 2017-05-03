<?php

class TakeDamageModification implements ModificationInterface
{

    /**
     * @var CreatureInterface
     */
    protected $target;

    /**
     * @var RolledDamage
     */
    protected $damage;

    /**
     * TakeDamageModification constructor.
     * @param CreatureInterface $target
     * @param $damage
     */
    public function __construct(CreatureInterface $target, RolledDamage $damage)
    {
        $this->target = $target;
        $this->damage = $damage;
    }

    public function execute(EventDispatcher $dispatcher)
    {
        $realDamage = $this->target->takeDamage($this->damage);
        $dispatcher->dispatch(new Event("modification.takeDamage", [ 'target' => $this->target, 'damage' => $this->damage]));
    }

    /**
     * @return CreatureInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return RolledDamage
     */
    public function getDamage()
    {
        return $this->damage;
    }

}
