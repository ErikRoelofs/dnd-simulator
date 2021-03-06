<?php

class IceMephit extends BaseCreature
{

    private $actions = [];

    /**
     * @var SpellPoolResource
     */
    private $spellPool;

   public function __construct($name, $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, 'Ice Mephit', 21,11, 1, [], [], $dispatcher);

        $this->spellPool = new SpellPoolResource(10, 5, [ 1 => 1 ]);

        $a = new ActionPool();
        $a->addAction(new AttackAction(3, damage("1d4+1", Damage::TYPE_SLASHING, "1d4", Damage::TYPE_COLD), 1));
        $a->addAction(new PassAction());
        $a->addAction(new SimpleDamageSpellAction(new SpellslotResource($this->spellPool, 1), damage("2d4", Damage::TYPE_COLD), [ ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE], 1, ActionInterface::TYPE_ACTION, Ability::DEXTERITY, "Ice Breath"));
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

        $this->vulnerabilities = [ Damage::TYPE_BLUDGEONING => true, Damage::TYPE_FIRE => true];
        $this->immunities = [ Damage::TYPE_COLD => true, Damage::TYPE_POISON => true];

        $this->gainEffect(new ActiveEffect("explode", new InterruptActionOnEnd("explode", $this->dispatcher), new InherentTerminator()));

    }

    public function getActions()
    {
        return $this->actions;
    }

    public function explode(Faction $myFaction, Faction $otherFaction) {
        $explode = new ExplodeAction(damage("1d8", Damage::TYPE_SLASHING), 10);
        return $explode->perform(new Perspective($this, $myFaction, $otherFaction, $this->dispatcher),[$otherFaction->getRandomCreature()]);
    }

}
