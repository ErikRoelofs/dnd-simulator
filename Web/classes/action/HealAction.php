<?php

class HealAction implements ActionInterface
{

    protected $amount;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }


    public function getType()
    {
        return ActionInterface::TYPE_BONUS;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $log = $perspective->getLog();
        foreach($targets as $target) {
            if(!$target) { return; }
            $cb = $this->amount;
            $heal = $cb();
            $target->healDamage($heal);
            $log->write($me->getName() . ' healed ' . $target->getName() . ' for ' . $heal . ' health', Log::MEDIUM_IMPORTANT);
        }
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_FRIENDLY_CREATURE ];
    }


}
