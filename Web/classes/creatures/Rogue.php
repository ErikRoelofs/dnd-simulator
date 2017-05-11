<?php

class Rogue extends BasePlayer
{

   public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), 'Tooky', 'Rogue', 9,14,3, [
            Ability::STRENGTH => true,
            Ability::DEXTERITY => true
        ], [
            Ability::STRENGTH => 1,
            Ability::DEXTERITY => 3,
            Ability::CONSTITUTION => 1,
            Ability::INTELLIGENCE => 0,
            Ability::WISDOM => 2,
            Ability::CHARISMA => 0
        ], 2, $dispatcher);
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
