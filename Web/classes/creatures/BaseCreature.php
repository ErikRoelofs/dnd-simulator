<?php

abstract class BaseCreature implements CreatureInterface
{

    protected $name;
    protected $type;
    protected $maxHP;
    protected $currentHP;
    protected $attackBonus;
    protected $ac;
    protected $initiative;

    public function __construct($name, $type, $hp, $ac, $attackBonus, $initiative) {
        $this->name = $name;
        $this->type = $type;
        $this->maxHP = $hp;
        $this->currentHP = $hp;
        $this->attackBonus = $attackBonus;
        $this->ac = $ac;
        $this->initiative = $initiative;
    }

    public function getMaxHP() {
        return $this->maxHP;
    }

    public function getCurrentHP()
    {
        return $this->currentHP;
    }

    public function takeDamage($damage)
    {
        $this->currentHP -= $damage;
    }

    public function getAC()
    {
        return $this->ac;
    }

    public function makeAttack(CreatureInterface $creature)
    {
        $roll = mt_rand(1,20) + $this->attackBonus;
        if( $roll >= $creature->getAC()) {
            $creature->takeDamage($this->doDamageRoll());
        }
    }

    abstract protected function doDamageRoll();

    public function isDead()
    {
        return $this->currentHP <= 0;
    }

    public function getInitiative()
    {
        return mt_rand(1,20) + $this->initiative;
    }

    public function getName()
    {
        return $this->name . ' the ' . $this->type;
    }

}
