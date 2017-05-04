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
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, 'Ice Mephit', 21,11,3, damage("1d4+1", Damage::TYPE_SLASHING, "1d4", Damage::TYPE_FIRE), 1, [], $dispatcher);

        $this->spellPool = new SpellPoolResource(10, 5, [ 1 => 1 ]);

        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new PassAction());
        $a->addAction(new SimpleDamageSpellAction($this->spellPool, damage("2d4", Damage::TYPE_COLD), [ ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE, ActionInterface::TARGET_UNIQUE_ENEMY_CREATURE], 1, ActionInterface::TYPE_ACTION, Ability::DEXTERITY, "Ice Breath"));
        $a->addAction(new PassMovementAction());
        $a->addAction(new PassBonusAction());
        $this->actions = $a;

        $this->vulnerabilities = [ Damage::TYPE_BLUDGEONING, Damage::TYPE_FIRE ];
        $this->immunities = [ Damage::TYPE_COLD, Damage::TYPE_POISON ];

    }

    public function getActions()
    {
        return $this->actions;
    }

    public function takeDamage(RolledDamage $damage)
    {
        $damage = parent::takeDamage($damage);
        if($this->currentHP === 0) {
            $this->dispatcher->dispatch(new Event(Round::EVENT_INTERRUPT, ['creature' => $this, 'method' => 'explode']));
        }
        return $damage;
    }

    public function explode(Faction $myFaction, Faction $otherFaction) {
       $explode = new ExplodeAction(damage("1d8", Damage::TYPE_SLASHING), 20);
       return $explode->perform(new Perspective($this, $myFaction, $otherFaction, $this->dispatcher),[$otherFaction->getRandomCreature()]);
    }


}
