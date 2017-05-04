<?php

class ExplodeAction implements ActionInterface
{

    const EVENT_SAVED = 'action.explode.miss';
    const EVENT_NOT_SAVED = 'action.explode.hit';

    /**
     * @var DamageExpression
     */
    protected $damageExpression;

    protected $saveDC;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct(DamageExpression $damageExpression, $saveDC)
    {
        $this->damageExpression = $damageExpression;
        $this->saveDC = $saveDC;
    }

    public function getType()
    {
        return ActionInterface::TYPE_FEATURE;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $roll = $this->damageExpression->roll();
            if(!$target->makeSave(Ability::DEXTERITY, $this->saveDC)) {
                $dispatcher->dispatch(new Event(self::EVENT_NOT_SAVED, ['caster' => $me, 'target' => $target, 'damage' => $roll]));
                return [ new TakeDamageModification($target, $roll) ];
            }
            $dispatcher->dispatch(new Event(self::EVENT_SAVED, ['caster' => $me, 'target' => $target]));
            return [];

        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_ENEMY_CREATURE ];
    }

    public function predict(Perspective $perspective, $targets)
    {
        return [];
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
