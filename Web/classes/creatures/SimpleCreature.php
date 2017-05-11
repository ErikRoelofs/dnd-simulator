<?php

class SimpleCreature extends BaseCreature
{
    protected $actions;

    public function __construct($name, $type, $hp, $ac, $attackBonus, $damage, $initiative, $saves, $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, $type, $hp, $ac, $initiative, $saves, $dispatcher);
        $a = new ActionPool();
        $a->addAction(new AttackAction($attackBonus, $damage, 1));
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
