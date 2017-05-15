<?php

class Cleric extends BasePlayer
{

    /**
     * @var ActionPool
     */
    protected $actions;

    /**
     * @var SpellPoolResource
     */
    protected $spellPool;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new HealFriendsGoal(1), new SlayEverythingGoal(5)]), 'Dwarfy', 'Cleric', 11,18, -1, [
            Ability::WISDOM => true,
            Ability::CHARISMA => true
        ], [
            Ability::STRENGTH => 2,
            Ability::DEXTERITY => -1,
            Ability::CONSTITUTION => 1,
            Ability::INTELLIGENCE => 0,
            Ability::WISDOM => 3,
            Ability::CHARISMA => 0
        ], 2, $dispatcher);
        $this->spellPool = new SpellPoolResource(12, 4);

        $a = new ActionPool();
        $a->addAction(new AttackAction(4, damage("1d8+2", Damage::TYPE_BLUDGEONING), 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new HealAction(dice("1d4+2"), new SpellslotResource($this->spellPool,1)));

        $effect = new ConditionEffect(function() use ($dispatcher) { return new ActiveEffect("Shield of Faith", new IncreaseAC(2), new ConcentrationTerminator($dispatcher, $this)); });
        $shieldOfFaith = new ModularAction(new TargetComponent([ActionInterface::TARGET_FRIENDLY_CREATURE]), [$effect], [new SpellslotResource($this->spellPool, 1)]);
        $a->addAction($shieldOfFaith);

        $effect = new ConditionEffect(function() use ($dispatcher) { return new ActiveEffect("Barkskin", new AddACCalculation(new FixedACCalculation(16)), new ConcentrationTerminator($dispatcher, $this)); });
        $barkskin = new ModularAction(new TargetComponent([ActionInterface::TARGET_FRIENDLY_CREATURE]), [$effect], [new SpellslotResource($this->spellPool, 1)]);
        $a->addAction($barkskin);

        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }

    public function getActions()
    {
        return $this->actions;
    }

}
