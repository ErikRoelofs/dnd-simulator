<?php

class HealAction implements ActionInterface, SpellInterface
{

    protected $amount;
    /**
     * @var SpellPoolResource
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
    public function __construct(DiceExpression $expr, SpellPoolResource $spellPoolResource)
    {
        $this->resource = $spellPoolResource;
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
            $dispatcher->dispatch(new Event("heal", [ 'healer' => $me, 'target' => $target, 'amount' => $heal]));
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_FRIENDLY_CREATURE ];
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        $healAvg = $this->diceExpression->avg();
        foreach($targets as $target) {
            if(!$target) { continue; }
            $mods[] = new HealDamageModification($target, $healAvg);
        }
        return $mods;
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return $this->resource->hasSlot($this->getSpellLevel());
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
