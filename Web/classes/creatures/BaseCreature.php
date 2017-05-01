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
    protected $attackBonus;
    protected $damage;
    protected $ac;
    protected $initiative;

    protected $saves = [];

    protected $resistances = [];
    protected $vulnerabilities = [];
    protected $immunities = [];

    /**
     * @var Condition[]
     */
    protected $conditions = [];

    public function __construct(StrategyInterface $strategy, $name, $type, $hp, $ac, $attackBonus, $damage, $initiative, $saves)
    {
        $this->strategy = $strategy;
        $this->name = $name;
        $this->type = $type;
        $this->maxHP = $hp;
        $this->currentHP = $hp;
        $this->attackBonus = $attackBonus;
        $this->ac = $ac;
        $this->initiative = $initiative;
        $this->damage = $damage;
        $this->saves = $saves;
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
        $realDamage = $this->calculateDamageAmount($damage);
        $this->currentHP -= $realDamage;
        return $realDamage;
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
        return min($taken, $this->currentHP);
    }

    public function getAC()
    {
        return $this->ac;
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

    public function takeTurn(Faction $myFaction, Faction $otherFaction, Log $log)
    {
        return $this->strategy->doTurn(new Perspective($this, $myFaction, $otherFaction, $log));
    }

    public function getActions()
    {
        $a = new ActionPool();
        $a->addAction(new AttackAction($this->attackBonus, $this->damage, 1));
        $a->addAction(new PassAction());
        $a->addAction(new PassBonusAction());
        $a->addAction(new PassMovementAction());
        return $a;
    }

    public function healDamage($heal)
    {
        $realHeal = min($heal, $this->maxHP - $this->currentHP);
        $this->currentHP += $realHeal;
        return $realHeal;
    }

    public function makeSave($type, $dc)
    {
        $bonus = 0;
        if (isset($this->saves[$type])) {
            $bonus = $this->saves[$type];
        }
        return mt_rand(1, 20) + $bonus > $dc;
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
        $rolled = $this->rollD20($this->getDieState(self::ROLL_ATTACK));

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

    public function getDieState($type, $data = null)
    {
        $advantage = false;
        $disadvantage = false;
        foreach($this->conditions as $condition) {
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

    public function gainCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }


}
