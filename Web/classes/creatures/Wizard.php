<?php

class Wizard extends BaseCreature
{

    private $actions = [];

    public function __construct()
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), 'Zappy', 'Wizard', 8,12,4,function(){ return mt_rand(1,6) +2; }, 2);

        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new HealAction(function() { return mt_rand(1,4) + 2; }));
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }

    public function getActions()
    {
        return $this->actions;
    }


}
