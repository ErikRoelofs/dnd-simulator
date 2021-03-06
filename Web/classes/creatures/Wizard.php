<?php

class Wizard extends BasePlayer
{

    private $actions = [];

    /**
     * @var SpellPoolResource
     */
    private $spellPool;

    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), 'Zappy', 'Wizard', 8,12,2, [
            Ability::WISDOM => true,
            Ability::INTELLIGENCE => true
        ], [
            Ability::STRENGTH => -1,
            Ability::DEXTERITY => 2,
            Ability::CONSTITUTION => 2,
            Ability::INTELLIGENCE => 3,
            Ability::WISDOM => 0,
            Ability::CHARISMA => 0
        ], 2, $dispatcher);
        $this->spellPool = new SpellPoolResource(13, 5);

        $a = new ActionPool();
        $a->addAction(new AttackAction(4, damage("1d6+2", Damage::TYPE_SLASHING), 1));
        $a->addAction(new PassAction());
        $a->addAction(new SimpleDamageSpellAction(new SpellslotResource($this->spellPool, 1), damage("3d4+3", Damage::TYPE_FORCE), [ ActionInterface::TARGET_ENEMY_CREATURE ], 1, ActionInterface::TYPE_ACTION, 0, "Magic Missile"));
        $a->addAction(new SimpleDamageSpellAction(new SpellslotResource($this->spellPool, 1), damage("3d6", Damage::TYPE_FIRE), [ ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE ], 1, ActionInterface::TYPE_ACTION, Ability::DEXTERITY, "Burning Hands"));
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }

    public function getActions()
    {
        return $this->actions;
    }

}
