<?php

class AttackRollEffect implements EffectComponentInterface
{

    const EVENT_MISS = 'action.attack.miss';
    const EVENT_HIT = 'action.attack.hit';
    const EVENT_CRIT = 'action.attack.crit';

    const ATTACK_MISS = 201;
    const ATTACK_HIT = 202;
    const ATTACK_CRIT = 203;

    protected $attackBonus;

    /**
     * @var EffectComponentInterface
     */
    protected $onCrit;
    /**
     * @var EffectComponentInterface
     */
    protected $onHit;
    /**
     * @var EffectComponentInterface
     */
    protected $onMiss;

    /**
     * AttackRollEffect constructor.
     * @param $attackBonus
     * @param EffectComponentInterface $onCrit
     * @param EffectComponentInterface $onHit
     * @param EffectComponentInterface $onMiss
     */
    public function __construct($attackBonus, EffectComponentInterface $onCrit, EffectComponentInterface $onHit, EffectComponentInterface $onMiss)
    {
        $this->attackBonus = $attackBonus;
        $this->onCrit = $onCrit;
        $this->onHit = $onHit;
        $this->onMiss = $onMiss;
    }


    public function perform(Perspective $perspective, array $targets)
    {
        $me = $perspective->getMe();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $state = $me->makeAttackRoll($this->attackBonus, $target);

            switch($state) {
                case self::ATTACK_CRIT: {
                    return $this->onCrit->perform($perspective, $targets);
                }
                case self::ATTACK_HIT: {
                    return $this->onHit->perform($perspective, $targets);
                }
                case self::ATTACK_MISS: {
                    return $this->onMiss->perform($perspective, $targets);
                }
            }
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        foreach($targets as $target) {
            $chanceToHit = (21 - ($target->getAC() - $this->attackBonus)) / 20;
            $mods = $this->onHit->predict($perspective, $targets);
            foreach($mods as $mod) {
                $outcomes[] = new Outcome([$mod], $chanceToHit);
            }
        }
        return new Prediction($outcomes);
    }

}
