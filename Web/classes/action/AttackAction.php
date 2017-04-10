<?php

class AttackAction implements ActionInterface
{

    protected $attackBonus;
    protected $damage;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($attackBonus, $damage)
    {
        $this->attackBonus = $attackBonus;
        $this->damage = $damage;
    }


    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $log = $perspective->getLog();
        foreach($targets as $target) {
            if(!$target) { return; }
            $roll = mt_rand(1,20) + $this->attackBonus;
            if( $roll >= $target->getAC()) {
                $cb = $this->damage;
                $dmg = $cb();
                $target->takeDamage($dmg);
                $log->write($me->getName() . ' hit ' . $target->getName() . ' with a ' . $roll . ' for ' . $dmg, Log::MEDIUM_IMPORTANT);
            }
            else {
                $log->write($me->getName() . ' missed ' . $target->getName() . ' with a ' . $roll, Log::MEDIUM_IMPORTANT );
            }
        }
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_CREATURE ];
    }


}
