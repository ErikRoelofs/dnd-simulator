<?php


interface CreatureInterface
{
    public function getMaxHP();
    public function getCurrentHP();
    public function takeDamage($damage);
    public function healDamage($heal);

    public function getAC();

    public function takeTurn(Faction $myFaction, Faction $otherFaction, Log $log);

    public function isDead();
    public function getInitiative();

    public function getName();
    public function getActions();

}
