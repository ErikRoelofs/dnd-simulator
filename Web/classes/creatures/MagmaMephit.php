<?php

class MagmaMephit extends BaseCreature
{

    private $actions = [];

    /**
     * @var SpellPoolResource
     */
    private $spellPool;

   public function __construct($name, $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, 'Magma Mephit', 22,11,1, [], [], $dispatcher);

        $this->spellPool = new SpellPoolResource(11, 5, [ 1 => 1 ]);

        $a = new ActionPool();
        $a->addAction(new AttackAction(3, damage("1d4+1", Damage::TYPE_SLASHING, "1d4", Damage::TYPE_FIRE), 1));
        $a->addAction(new PassAction());
        $a->addAction(new SimpleDamageSpellAction(new SpellslotResource($this->spellPool, 1), damage("2d6", Damage::TYPE_FIRE), [ ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE], 1, ActionInterface::TYPE_ACTION, Ability::DEXTERITY, "Fire Breath"));
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

        $this->vulnerabilities = [ Damage::TYPE_COLD => true];
        $this->immunities = [ Damage::TYPE_FIRE => true, Damage::TYPE_POISON => true];

        $this->gainEffect(new ActiveEffect("explode", new InterruptActionOnEnd("explode", $this->dispatcher), new InherentTerminator()));

    }

    public function getActions()
    {
        return $this->actions;
    }

    public function explode(Faction $myFaction, Faction $otherFaction) {
        $explode = new ExplodeAction(damage("2d6", Damage::TYPE_FIRE), 11);
        return $explode->perform(new Perspective($this, $myFaction, $otherFaction, $this->dispatcher),[$otherFaction->getRandomCreature()]);
    }


}
