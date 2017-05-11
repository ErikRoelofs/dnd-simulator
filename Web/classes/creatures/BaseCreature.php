<?php

abstract class BaseCreature implements CreatureInterface
{

    /**
     * @var StrategyInterface
     */
    protected $strategy;
    protected $name;
    protected $type;
    protected $maxHP;
    protected $currentHP;

    /**
     * @var AC
     */
    protected $ac;
    protected $initiative;

    protected $abilities = [];
    protected $saves = [];


    protected $resistances = [];
    protected $vulnerabilities = [];
    protected $immunities = [];

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var ActiveEffect[]
     */
    protected $effects = [];

    public function __construct(StrategyInterface $strategy, $name, $type, $hp, $ac, $initiative, $saves, $abilities, $dispatcher)
    {
        $this->strategy = $strategy;
        $this->name = $name;
        $this->type = $type;
        $this->maxHP = $hp;
        $this->currentHP = $hp;
        $this->ac = new AC($this, [new FixedACCalculation($ac)]);
        $this->initiative = $initiative;
        $this->abilities = $abilities;
        $this->saves = $saves;
        $this->dispatcher = $dispatcher;
    }

    public function getMaxHP()
    {
        return $this->maxHP;
    }

    public function getCurrentHP()
    {
        return $this->currentHP;
    }

    public function takeDamage(RolledDamage $damage)
    {
        if($this->currentHP === 0) {
            return;
        }
        $hpLost = $this->calculateDamageAmount($damage);
        $this->currentHP -= $hpLost;
        $this->dispatcher->dispatch(new Event(self::EVENT_TAKE_DAMAGE, [ 'target' => $this, 'hpLost' => $hpLost]));
        if($this->currentHP === 0) {
            $this->dispatcher->dispatch(new Event(CreatureInterface::EVENT_DOWNED, ['creature' => $this]));
        }
        return $hpLost;
    }

    public function predictDamageTaken(RolledDamage $damage)
    {
        return $this->calculateDamageAmount($damage);
    }

    protected function calculateDamageAmount(RolledDamage $damage)
    {
        $taken = 0;
        foreach ($damage->getRolls() as $roll) {
            if (isset($this->immunities[$roll->getType()])) {

            } elseif (isset($this->resistances[$roll->getType()])) {
                $taken += floor($roll->getAmount() * 0.5);
            } elseif (isset($this->vulnerabilities[$roll->getType()])) {
                $taken += $roll->getAmount() * 2;
            } else {
                $taken += $roll->getAmount();
            }
        }
        return (int) min($taken, $this->currentHP);
    }

    public function getAC()
    {
        return $this->ac->getCurrentAC();
    }

    public function isDead()
    {
        return $this->currentHP <= 0;
    }

    public function getInitiative()
    {
        return mt_rand(1, 20) + $this->initiative;
    }

    public function getName()
    {
        return $this->name . ' the ' . $this->type;
    }

    public function takeTurn(Faction $myFaction, Faction $otherFaction)
    {
        $this->dispatcher->dispatch(new Event(self::EVENT_START_TURN, ['creature' => $this]));
        $outcome = $this->strategy->doTurn(new Perspective($this, $myFaction, $otherFaction, $this->dispatcher));
        $this->dispatcher->dispatch(new Event(self::EVENT_END_TURN, ['creature' => $this]));
        return $outcome;
    }

    abstract public function getActions();

    public function healDamage($heal)
    {
        $realHeal = min($heal, $this->maxHP - $this->currentHP);
        $this->currentHP += $realHeal;
        return $realHeal;
    }

    public function makeSave($type, $dc)
    {
        if($this->getOverride(self::ROLL_SAVE, $type)) {
            $rolled = $this->getOverride(self::ROLL_SAVE, $type);
        }
        else {
            $rolled = $this->rollD20($this->getDieState(self::ROLL_SAVE, $type));
        }
        $bonus = isset($this->saves[$type]) ? $this->saves[$type] : 0;
        return $rolled + $bonus >= $dc;
    }

    public function predictSave($type, $dc)
    {
        $bonus = 21;
        if (isset($this->saves[$type])) {
            $bonus += $this->saves[$type];
        }
        return ($bonus - $dc) * 0.05;
    }

    public function makeAttackRoll($bonus, CreatureInterface $target)
    {
        if($this->getOverride(self::ROLL_ATTACK)) {
            $rolled = $this->getOverride(self::ROLL_ATTACK);
        }
        elseif($target->getOverride(self::ROLL_ATTACKED)) {
            $rolled = $target->getOverride(self::ROLL_ATTACKED);
        }
        else {
            $myState = $this->getDieState(self::ROLL_ATTACK);
            $opponentState = $target->getDieState(self::ROLL_ATTACKED);
            $state = $this->determineDieState($myState, $opponentState);
            $rolled = $this->rollD20($state);
        }

        if ($rolled === 20) {
            return ActionInterface::ATTACK_CRIT;
        }
        if ($rolled + $bonus > $target->getAC()) {
            return ActionInterface::ATTACK_HIT;
        }
        return ActionInterface::ATTACK_MISS;
    }

    public function makeDamageRoll($hitType, DamageExpression $damageExpression, CreatureInterface $target)
    {
        $dmg = $damageExpression->roll();
        if ($hitType === ActionInterface::ATTACK_CRIT) {
            $dmg = $dmg->add($damageExpression->rollDiceOnly());
        }
        return $dmg;
    }

    public function predictAttackRoll($bonus, CreatureInterface $target)
    {
        $chanceToHit = (21 - ($target->getAC() - $bonus)) / 20;
        return [
            ['type' => AttackRollEffect::ATTACK_CRIT, 'chance' => 0.05],
            ['type' => AttackRollEffect::ATTACK_HIT, 'chance' => $chanceToHit - 0.05], // because a crit is also a hit
            ['type' => AttackRollEffect::ATTACK_MISS, 'chance' => 1 - $chanceToHit],
        ];
    }

    public function predictDamageRoll($hitType, DamageExpression $damageExpression, CreatureInterface $target)
    {
        $dmg = $damageExpression->avg();
        if ($hitType === ActionInterface::ATTACK_CRIT) {
            $dmg = $dmg->add($damageExpression->avgDiceOnly());
        }
        return $dmg;
    }



    // we assume there are never multiple overrides with different effects
    // because no conditions exist as far as I know that enable this
    public function getOverride($type, $data = null) {
        foreach($this->iterateConditions() as $condition) {
            if($condition->replaceRoll($type, $data)) {
                return $condition->replaceRoll($type, $data);
            }
        }
    }

    public function getDieState($type, $data = null)
    {
        $advantage = false;
        $disadvantage = false;
        foreach($this->iterateConditions() as $condition) {
            $change = $condition->modifiesRoll($type, $data);
            if($change === CreatureInterface::DIE_DISADVANTAGE) {
                $disadvantage = true;
            }
            if($change === CreatureInterface::DIE_ADVANTAGE) {
                $advantage = true;
            }
        }
        if($advantage && !$disadvantage) {
            return CreatureInterface::DIE_ADVANTAGE;
        }
        if($disadvantage && !$advantage) {
            return CreatureInterface::DIE_DISADVANTAGE;
        }
        return CreatureInterface::DIE_NORMAL;
    }

    protected function rollD20($rollType)
    {
        $roll1 = mt_rand(1, 20);
        $roll2 = mt_rand(1, 20);
        if ($rollType === self::DIE_ADVANTAGE) {
            return max($roll1, $roll2);
        } elseif ($rollType === self::DIE_DISADVANTAGE) {
            return min($roll1, $roll2);
        } else {
            return $roll1;
        }
    }

    public function getAvailableActions()
    {
        $actions = [ActionInterface::TYPE_ACTION => true, ActionInterface::TYPE_BONUS => true, ActionInterface::TYPE_MOVEMENT => true];;
        foreach($this->iterateConditions() as $condition) {
            foreach($condition->restrictsAvailableActions() as $action) {
                unset($actions[$action]);
            }
        }
        return $actions;
    }

    private function determineDieState($state1, $state2) {
        if($state1 === self::DIE_ADVANTAGE && $state2 !== self::DIE_DISADVANTAGE) {
            return self::DIE_ADVANTAGE;
        }
        if($state1 !== self::DIE_ADVANTAGE && $state2 === self::DIE_DISADVANTAGE) {
            return self::DIE_DISADVANTAGE;
        }
        return self::DIE_NORMAL;
    }

    private function iterateConditions() {
        return array_map(function(ActiveEffect $item) {
            return $item->getCondition();
        }, $this->effects);
    }

    public function gainEffect(ActiveEffect $effect)
    {
        $effect->setOwner($this);
        $this->effects[spl_object_hash($effect)] = $effect;
        $this->dispatcher->dispatch(new Event(self::EVENT_GAINED_CONDITION, ['effect' => $effect, 'creature' => $this]));
    }

    public function loseEffect(ActiveEffect $effect)
    {
        if(!isset($this->effects[spl_object_hash($effect)])) {
            throw new Exception("Cannot lose effect; not on this creature.");
        }
        unset($this->effects[spl_object_hash($effect)]);
        $this->dispatcher->dispatch(new Event(self::EVENT_LOST_CONDITION, ['effect' => $effect, 'creature' => $this]));
    }

    public function addVulnerability($vulnerability) {
        $this->vulnerabilities[$vulnerability] = true;
    }
    public function addResistance($resistance) {
        $this->resistances[$resistance] = true;
    }
    public function addImmunity($immunity) {
        $this->immunities[$immunity] = true;
    }

}
