<?php

class TestBro extends BaseCreature
{

    private $actions;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1), new HealFriendsGoal(1), new ConserveResourcesGoal(1)]), 'Beefcake', 'Testbro', 500,17,5,damage("1d12+3", Damage::TYPE_SLASHING), -1, [], $dispatcher);
        $a = new ActionPool();

        $mod = new ModularAction(
            new TargetComponent([ActionInterface::TARGET_ENEMY_CREATURE]),
            [ new AttackEffect(5, damage("1d12+3", Damage::TYPE_SLASHING)) ],
            []
        );

        $a->addAction($mod);
        $a->addAction(new SecondWindAction(dice("1d10+1")));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;
    }

    public function getActions()
    {
        return $this->actions;
    }


}
