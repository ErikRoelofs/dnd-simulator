<?php

class HealAction implements ActionInterface, SpellInterface
{

    protected $amount;
    /**
     * @var SpellPoolResource
     */
    protected $resource;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($amount, SpellPoolResource $spellPoolResource)
    {
        $this->amount = $amount;
        $this->resource = $spellPoolResource;
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
            $cb = $this->amount;
            $heal = $cb();
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
        $healAvg = 0;
        foreach($targets as $target) {
            if(!$target) { continue; }
            $cb = $this->amount;
            for($i = 1; $i < 50; $i++ ) {
                $heal = $cb();
                $healAvg = (($healAvg * $i) + $heal ) / ( $i + 1 );
            }
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
