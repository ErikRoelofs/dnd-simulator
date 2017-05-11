<?php

class SimpleCreature extends BaseCreature
{
    protected $actions;

    public function __construct($name, $type, $hp, $ac, $attackBonus, $damage, $initiative, $saves, $dispatcher)
    {
        $abilities = [
            Ability::STRENGTH => 0,
            Ability::DEXTERITY => 0,
            Ability::CONSTITUTION => 0,
            Ability::INTELLIGENCE => 0,
            Ability::WISDOM => 0,
            Ability::CHARISMA => 0,
        ];
        foreach($abilities as $ability => $value) {
            if(!isset($saves[$ability])) {
                $saves[$ability] = $value;
            }
        }
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, $type, $hp, $ac, $initiative, $saves, $abilities, $dispatcher);
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
