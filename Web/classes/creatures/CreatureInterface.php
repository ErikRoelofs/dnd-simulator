<?php


interface CreatureInterface
{

    const ROLL_ATTACK = 1;
    const ROLL_ABILITY = 2;
    const ROLL_SAVE = 3;
    const ROLL_ATTACKED = 4;

    const DIE_ADVANTAGE = 101;
    const DIE_NORMAL = 102;
    const DIE_DISADVANTAGE = 103;

    const EVENT_START_TURN = 'creature.turn.start';
    const EVENT_END_TURN = 'creature.turn.end';
    const EVENT_DOWNED = 'creature.downed';
    const EVENT_TAKE_DAMAGE = 'creature.takeDamage';

    const EVENT_GAINED_CONDITION = 'creature.conditions.gained';
    const EVENT_LOST_CONDITION = 'creature.conditions.lost';

    public function getMaxHP();
    public function getCurrentHP();

    public function getAC();

    public function takeTurn(Faction $myFaction, Faction $otherFaction);

    public function isDead();
    public function getInitiative();

    public function getName();
    public function getActions();

    public function takeDamage(RolledDamage $damage);
    public function predictDamageTaken(RolledDamage $damage);
    public function healDamage($heal);

    public function makeSave($type, $dc);
    public function predictSave($type, $dc);

    public function makeAttackRoll($bonus, CreatureInterface $target);
    public function makeDamageRoll($hitType, DamageExpression $damageExpression, CreatureInterface $target);

    public function getDieState($type, $data = null);

    public function gainEffect(ActiveEffect $effect);
    public function loseEffect(ActiveEffect $effect);

    public function getAvailableActions();
}
