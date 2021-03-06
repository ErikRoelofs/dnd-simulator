<?php

class DamageEffect implements EffectComponentInterface
{

    /**
     * @var DamageExpression
     */
    protected $damageExpression;

    /**
     * HitEffect constructor.
     * @param $damageExpression
     */
    public function __construct(DamageExpression $damageExpression)
    {
        $this->damageExpression = $damageExpression;
    }

    public function perform(Perspective $perspective, array $targets)
    {
        $dmg = $this->damageExpression->roll();
        $mods = [];
        foreach($targets as $target) {
            $mods[] = new TakeDamageModification($target, $dmg);
            $perspective->getDispatcher()->dispatch(new Event(SimpleDamageSpellAction::EVENT_NOT_SAVED, ['caster' => $perspective->getMe(), 'target' => $target, 'damage' => $dmg]));
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $dmg = $this->damageExpression->avg();
        $mods = [];
        foreach($targets as $target) {
            $mods[] = new TakeDamageModification($target, $dmg);
        }
        return $mods;
    }

}
