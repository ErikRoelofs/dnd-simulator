<?php

class Fighter extends BaseCreature
{

    private $actions;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1), new HealFriendsGoal(1), new ConserveResourcesGoal(1)]), 'Jimbob', 'Fighter', 12,17,5,damage("1d12+3", Damage::TYPE_SLASHING), -1, [], $dispatcher);
        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1, 'Greataxe'));
        $a->addAction(new AttackAction($this->attackBonus, damage("1d8+3", Damage::TYPE_BLUDGEONING), 1, 'Warhammer'));
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
