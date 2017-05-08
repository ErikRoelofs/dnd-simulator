<?php

class AttackAction implements ActionInterface
{

    const EVENT_MISS = 'action.attack.miss';
    const EVENT_HIT = 'action.attack.hit';
    const EVENT_CRIT = 'action.attack.crit';

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
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $state = $me->makeAttackRoll($this->attackBonus, $target);

            if( $state === ActionInterface::ATTACK_CRIT || $state === ActionInterface::ATTACK_HIT ) {
                $dmg = $me->makeDamageRoll($state, $this->damageExpression, $target);
                $mods[] = new TakeDamageModification($target, $dmg);
                $event = $state === ActionInterface::ATTACK_CRIT ? self::EVENT_CRIT : self::EVENT_HIT;
                $dispatcher->dispatch(new Event($event, ['attacker' => $me, 'target' => $target, 'damage' => $dmg]));
            }
            else {
                $dispatcher->dispatch(new Event(self::EVENT_MISS, ['attacker' => $me, 'target' => $target, 'damage' => null]));
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
        $outcomes = [];
        foreach($targets as $target) {
            $chanceToHit = (21 - ($target->getAC() - $this->attackBonus)) / 20;
            $mod = new TakeDamageModification($target, $this->damageExpression->avg());
            $outcomes[] = new Outcome([$mod], $chanceToHit);
        }
        return new Prediction($outcomes);
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
