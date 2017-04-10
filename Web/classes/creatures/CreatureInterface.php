<?php


interface CreatureInterface
{
    public function getMaxHP();
    public function getCurrentHP();
    public function takeDamage($damage);

    public function getAC();
    public function makeAttack(CreatureInterface $creature, Log $log);

    public function isDead();
    public function getInitiative();

    public function getName();
}
