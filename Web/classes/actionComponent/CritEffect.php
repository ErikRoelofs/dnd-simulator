<?php

class CritEffect implements EffectComponentInterface
{

    protected $damageExpression;

    /**
     * HitEffect constructor.
     * @param $damageExpression
     */
    public function __construct($damageExpression)
    {
        $this->damageExpression = $damageExpression;
    }

    public function perform(Perspective $perspective, array $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            $dmg = $perspective->getMe()->makeDamageRoll(AttackEffect::EVENT_CRIT, $this->damageExpression, $target);
            $mods[] = new TakeDamageModification($target, $dmg);
            $perspective->getDispatcher()->dispatch(new Event(AttackEffect::EVENT_CRIT, ['attacker' => $perspective->getMe(), 'target' => $target, 'damage' => $dmg]));
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            $dmg = $perspective->getMe()->makeDamageRoll(AttackEffect::EVENT_CRIT, $this->damageExpression, $target);
            $mods[] = new TakeDamageModification($target, $dmg);
        }
        return $mods;
    }

}
