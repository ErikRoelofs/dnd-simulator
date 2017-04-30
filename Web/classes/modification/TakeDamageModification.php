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

    public function execute(Log $log)
    {
        $realDamage = $this->target->takeDamage($this->damage);
        $log->write($this->target->getName() . " has taken " . $realDamage . " points of damage.", Log::MEDIUM_IMPORTANT);
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
