<?php

class Rogue extends BaseCreature
{

   public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), 'Tooky', 'Rogue', 9,14,3, [],$dispatcher);
        $a = new ActionPool();
        $a->addAction(new AttackAction(5, damage("2d6+3"), 1));
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
