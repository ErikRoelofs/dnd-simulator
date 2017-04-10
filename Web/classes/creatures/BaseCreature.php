<?php

abstract class BaseCreature implements CreatureInterface
{

    /**
     * @var StrategyInterface
     */
    protected $strategy;
    protected $name;
    protected $type;
    protected $maxHP;
    protected $currentHP;
    protected $attackBonus;
    protected $damage;
    protected $ac;
    protected $initiative;

    public function __construct(StrategyInterface $strategy, $name, $type, $hp, $ac, $attackBonus, $damage, $initiative) {
        $this->strategy = $strategy;
        $this->name = $name;
        $this->type = $type;
        $this->maxHP = $hp;
        $this->currentHP = $hp;
        $this->attackBonus = $attackBonus;
        $this->ac = $ac;
        $this->initiative = $initiative;
        $this->damage = $damage;
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

    public function takeTurn(Faction $myFaction, Faction $otherFaction, Log $log)
    {
        $this->strategy->doTurn(new Perspective($this, $myFaction, $otherFaction, $log));
    }

    public function getActions() {
        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        return $a;
    }

}
