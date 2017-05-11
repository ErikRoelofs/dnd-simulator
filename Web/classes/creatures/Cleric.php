<?php

class Cleric extends BaseCreature
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
        parent::__construct(new BasicStrategy([new HealFriendsGoal(1), new SlayEverythingGoal(5)]), 'Dwarfy', 'Cleric', 11,18, -1, [], $dispatcher);
        $this->spellPool = new SpellPoolResource(12, 4);

        $a = new ActionPool();
        $a->addAction(new AttackAction(4, damage("1d8+2", Damage::TYPE_BLUDGEONING), 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new HealAction(dice("1d4+2"), $this->spellPool));
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }

    public function getActions()
    {
        return $this->actions;
    }

}
