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

    public function makeAttack(CreatureInterface $creature, Log $log)
    {
        $roll = mt_rand(1,20) + $this->attackBonus;
        if( $roll >= $creature->getAC()) {
            $dmg = $this->doDamageRoll();
            $creature->takeDamage($dmg);
            $log->write($this->getName() . ' hit ' . $creature->getName() . ' with a ' . $roll . ' for ' . $dmg, Log::MEDIUM_IMPORTANT);
        }
        else {
            $log->write($this->getName() . ' missed ' . $creature->getName() . ' with a ' . $roll, Log::MEDIUM_IMPORTANT );
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

    public function takeTurn(Faction $myFaction, Faction $otherFaction, Log $log)
    {
        $target = $otherFaction->getRandomCreature();
        if(!$target) {
            return null;
        }
        $this->makeAttack($target, $log);
    }


}
