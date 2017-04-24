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

    public function __construct()
    {
        parent::__construct(new BasicStrategy([new HealFriendsGoal(1), new SlayEverythingGoal(5)]), 'Dwarfy', 'Cleric', 11,18,4,function(){return mt_rand(1,8) +2;}, -1);
        $this->spellPool = new SpellPoolResource();

        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassMovementAction());
        $a->addAction(new HealAction(function() { return mt_rand(1,4) + 2; }, $this->spellPool));
        $a->addAction(new DivineFireAction($this->spellPool));
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

    }

    public function getActions()
    {
        return $this->actions;
    }

}
