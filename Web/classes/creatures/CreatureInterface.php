<?php


interface CreatureInterface
{
    public function getMaxHP();
    public function getCurrentHP();

    public function getAC();

    public function takeTurn(Faction $myFaction, Faction $otherFaction, Log $log);

    public function isDead();
    public function getInitiative();

    public function getName();
    public function getActions();

    public function takeDamage(RolledDamage $damage);
    public function predictDamageTaken(RolledDamage $damage);
    public function healDamage($heal);

}
