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
        $log = $perspective->getLog();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $heal = $this->diceExpression->roll();
            $mods[] = new HealDamageModification($target, $heal);
            $log->write($me->getName() . ' healed ' . $target->getName() . ' for ' . $heal . ' health', Log::MEDIUM_IMPORTANT);
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
