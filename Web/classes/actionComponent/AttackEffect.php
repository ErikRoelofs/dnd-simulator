<?php

class AttackEffect implements EffectComponentInterface
{

    const EVENT_MISS = 'action.attack.miss';
    const EVENT_HIT = 'action.attack.hit';
    const EVENT_CRIT = 'action.attack.crit';

    protected $attackBonus;
    protected $damageExpression;

    /**
     * AttackEffect constructor.
     * @param $attackBonus
     * @param $damageExpression
     */
    public function __construct($attackBonus, $damageExpression)
    {
        $this->attackBonus = $attackBonus;
        $this->damageExpression = $damageExpression;
    }

    public function perform(Perspective $perspective, array $targets)
    {
        $me = $perspective->getMe();
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $state = $me->makeAttackRoll($this->attackBonus, $target);

            if( $state === ActionInterface::ATTACK_CRIT || $state === ActionInterface::ATTACK_HIT ) {
                $dmg = $me->makeDamageRoll($state, $this->damageExpression, $target);
                $mods[] = new TakeDamageModification($target, $dmg);
                $event = $state === ActionInterface::ATTACK_CRIT ? self::EVENT_CRIT : self::EVENT_HIT;
                $dispatcher->dispatch(new Event($event, ['attacker' => $me, 'target' => $target, 'damage' => $dmg]));
            }
            else {
                $dispatcher->dispatch(new Event(self::EVENT_MISS, ['attacker' => $me, 'target' => $target, 'damage' => null]));
            }
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        foreach($targets as $target) {
            $chanceToHit = (21 - ($target->getAC() - $this->attackBonus)) / 20;
            $mod = new TakeDamageModification($target, $this->damageExpression->avg());
            $outcomes[] = new Outcome([$mod], $chanceToHit);
        }
        return new Prediction($outcomes);
    }

}
