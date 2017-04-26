<?php

class AttackAction implements ActionInterface
{

    protected $attackBonus;

    /**
     * @var DamageExpression
     */
    protected $damageExpression;

    protected $attacks;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($attackBonus, DamageExpression $damageExpression, $attacks = 1)
    {
        $this->attackBonus = $attackBonus;
        $this->damageExpression = $damageExpression;
        $this->attacks = $attacks;
    }


    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $log = $perspective->getLog();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $roll = mt_rand(1,20) + $this->attackBonus;
            if( $roll >= $target->getAC()) {
                $dmg = $this->damageExpression->roll();
                $mods[] = new TakeDamageModification($target, $dmg);
                $log->write($me->getName() . ' hit ' . $target->getName() . ' with a ' . $roll . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
            }
            else {
                $log->write($me->getName() . ' missed ' . $target->getName() . ' with a ' . $roll, Log::MEDIUM_IMPORTANT );
            }
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        $slots = [];
        for( $i = 0 ; $i < $this->attacks ; $i++ ) {
            $slots[] = ActionInterface::TARGET_ENEMY_CREATURE;
        }
        return $slots;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $chanceToHit = (21 - ($target->getAC() - $this->attackBonus)) / 20;
            $avgDmg = $this->damageExpression->avg()->multiply($chanceToHit);
            $mods[] = new TakeDamageModification($target, $avgDmg);
        }
        return $mods;
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return true;
    }

    public function getResourceCost()
    {
        return [];
    }


}
