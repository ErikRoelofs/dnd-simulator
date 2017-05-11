<?php

class TestBro extends BasePlayer
{

    private $actions;

    /**
     * @var SpellPoolResource
     */
    protected $spellbook;

    /**
     * @var ConcentrationResource
     */
    protected $concentration;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1), new HealFriendsGoal(1), new ConserveResourcesGoal(1)]), 'Beefcake', 'Testbro', 500,17,-1, [
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
        $this->spellbook = new SpellPoolResource(11, 5);
        $this->concentration = new ConcentrationResource($this);

        $dmg = damage("1d12+3", Damage::TYPE_SLASHING);
        $mod = new ModularAction(
            new TargetComponent([ActionInterface::TARGET_ENEMY_CREATURE]),
            [   new AttackRollEffect(5, new CritEffect($dmg), new HitEffect($dmg), new MissEffect()),
                new ConditionEffect(function() { return new ActiveEffect('paralysed', new Paralysed(), new ConcentrationTerminator($this->dispatcher, $this));})],
            []
        );
        $mod2 = new ModularAction(
            new TargetComponent([ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE]),
            [ new SavingThrowEffect($this->spellbook->getSaveDC(), Ability::DEXTERITY, new DamageEffect(damage("3d6", Damage::TYPE_FIRE)), new HalfDamageEffect(damage("3d6", Damage::TYPE_FIRE))) ],
            [ new SpellslotResource($this->spellbook, 1) ]
        );

        $a->addAction($mod);
//        $a->addAction($mod2);
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
