<?php

class Fighter extends BasePlayer
{

    private $actions;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1), new HealFriendsGoal(1), new ConserveResourcesGoal(1)]), 'Jimbob', 'Fighter', 12,0, -1, [
            Ability::STRENGTH => true,
            Ability::CONSTITUTION => true
        ], [
            Ability::STRENGTH => 3,
            Ability::DEXTERITY => -1,
            Ability::CONSTITUTION => 2,
            Ability::INTELLIGENCE => 0,
            Ability::WISDOM => 1,
            Ability::CHARISMA => 0
        ], 2, $dispatcher);
        $a = new ActionPool();

        $attack1 = new AttackAction(5, damage("1d12+3", Damage::TYPE_SLASHING), 1, 'Greataxe');
        $attack2 = new AttackAction(5, damage("1d8+3", Damage::TYPE_BLUDGEONING), 1, 'Warhammer');
        $a->addAction($attack1);
        $a->addAction($attack2);
        $a->addAction(new MultiAttackAction([$attack2, $attack1]));
        $a->addAction(new SecondWindAction(dice("1d10+1")));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

        $this->ac->addCalculation(new HeavyArmorACCalculation($this, 16));
        $this->gainEffect(new ActiveEffect("Defensive Fighting", new DefensiveFightingStyle(), new InherentTerminator()));
    }

    public function getActions()
    {
        return $this->actions;
    }


}
