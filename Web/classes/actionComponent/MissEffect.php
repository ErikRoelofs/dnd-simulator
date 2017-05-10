<?php

class MissEffect implements EffectComponentInterface
{
    public function perform(Perspective $perspective, array $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            $perspective->getDispatcher()->dispatch(new Event(AttackEffect::EVENT_MISS, ['attacker' => $perspective->getMe(), 'target' => $target, 'damage' => null]));
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        return [];
    }

}
