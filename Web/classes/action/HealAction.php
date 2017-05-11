<?php

class HealAction implements ActionInterface, SpellInterface
{

    const EVENT_CAST = 'action.spell.heal';

    protected $amount;
    /**
     * @var SpellslotResource
     */
    protected $resource;

    /**
     * @var DiceExpression
     */
    private $diceExpression;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct(DiceExpression $expr, SpellslotResource $spellslotResource)
    {
        $this->resource = $spellslotResource;
        $this->diceExpression = $expr;
    }


    public function getType()
    {
        return ActionInterface::TYPE_BONUS;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $heal = $this->diceExpression->roll();
            $mods[] = new HealDamageModification($target, $heal);
            $dispatcher->dispatch(new Event(self::EVENT_CAST, [ 'healer' => $me, 'target' => $target, 'amount' => $heal]));
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_FRIENDLY_CREATURE ];
    }

    public function predict(Perspective $perspective, $targets)
    {
        $healAvg = $this->diceExpression->avg();
        $outcomes = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $outcomes[] = new Outcome([new HealDamageModification($target, $healAvg)], 1);
        }
        return new Prediction($outcomes);
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return $this->resource->available($this);
    }

    public function getResourceCost()
    {
        return [ $this->resource ];
    }

    public function getSpellLevel()
    {
        return 1;
    }


}
