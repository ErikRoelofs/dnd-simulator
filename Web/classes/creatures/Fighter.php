<?php

class Fighter extends BaseCreature
{

    private $actions;

    public function __construct()
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1), new HealFriendsGoal(1)]), 'Jimbob', 'Fighter', 12,17,5,function() { return mt_rand(1,12)+3; }, -1);
        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new SecondWindAction(function() { return mt_rand(1,10) + 1; }));
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
