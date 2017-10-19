<?php

class Hobgoblin extends SimpleCreature
{
    public function __construct($name, $dispatcher)
    {
        $dmg = damage("1d8+1", Damage::TYPE_SLASHING);
        parent::__construct($name, 'Hobgoblin', 11, 18, 3, $dmg, 1, [], $dispatcher);

        $attack = new ModularAction(
            new TargetComponent([ActionInterface::TARGET_ENEMY_CREATURE]),
            [ new AttackRollEffect(3,
                new CritEffect($dmg),
                new CombinedEffect([
                    new HitEffect($dmg),
                    new ConditionEffect(function() {
                        return new ActiveEffect("poisoned", new Poisoned(), new StartOfNextTurnTerminator($this->dispatcher));
                    })
                ]),
                new MissEffect()
            )],
            []
        );

        $a = new ActionPool();
        $a->addAction($attack);
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }


}
