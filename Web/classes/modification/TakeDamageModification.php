<?php

class TakeDamageModification implements ModificationInterface
{

    /**
     * @var CreatureInterface
     */
    protected $target;

    protected $damage;

    /**
     * TakeDamageModification constructor.
     * @param CreatureInterface $target
     * @param $damage
     */
    public function __construct(CreatureInterface $target, $damage)
    {
        $this->target = $target;
        $this->damage = $damage;
    }

    public function execute()
    {
        $this->target->takeDamage($this->damage);
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
    public function getDamage()
    {
        return $this->damage;
    }

}
