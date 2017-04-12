<?php

class Cleric extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BasicStrategy([new HealFriendsGoal(1), new SlayEverythingGoal(5)]), 'Dwarfy', 'Cleric', 11,18,4,function(){return mt_rand(1,8) +2;}, -1);
    }

    public function getActions()
    {
        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new HealAction(function() { return mt_rand(1,4) + 2; }));
        $a->addAction(new PassBonusAction());
        return $a;
    }

}
