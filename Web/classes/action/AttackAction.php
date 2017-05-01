<?php

class AttackAction implements ActionInterface
{

    protected $attackBonus;

    /**
     * @var DamageExpression
     */
    protected $damageExpression;

    protected $attacks;

    protected $weapon;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($attackBonus, DamageExpression $damageExpression, $attacks = 1, $weapon = 'weapon')
    {
        $this->attackBonus = $attackBonus;
        $this->damageExpression = $damageExpression;
        $this->attacks = $attacks;
        $this->weapon = $weapon;
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
            $state = $me->makeAttackRoll($this->attackBonus, $target);

            if( $state === ActionInterface::ATTACK_CRIT || $state === ActionInterface::ATTACK_HIT ) {
                $dmg = $me->makeDamageRoll($state, $this->damageExpression, $target);
                $mods[] = new TakeDamageModification($target, $dmg);
                $hit = $state === ActionInterface::ATTACK_CRIT ? ' critically hit ' : ' hit ';
                $log->write($me->getName() . $hit . $target->getName() . ' with their ' . $this->weapon . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
            }
            else {
                $log->write($me->getName() . ' missed ' . $target->getName() , Log::MEDIUM_IMPORTANT );
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
