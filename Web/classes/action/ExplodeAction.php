<?php

class ExplodeAction implements ActionInterface
{

    const EVENT_SAVED = 'action.explode.miss';
    const EVENT_NOT_SAVED = 'action.explode.hit';
    const EVENT_NO_DAMAGE = 'action.explode.nothing';

    /**
     * @var DamageExpression
     */
    protected $damageExpression;

    protected $saveDC;

    protected $ability;

    protected $saveHalves;

    /**
     * ExplodeAction constructor.
     * @param DamageExpression $damageExpression
     * @param $saveDC
     * @param $ability
     * @param $saveHalves
     */
    public function __construct(DamageExpression $damageExpression, $saveDC, $ability = Ability::DEXTERITY, $saveHalves = true)
    {
        $this->damageExpression = $damageExpression;
        $this->saveDC = $saveDC;
        $this->ability = $ability;
        $this->saveHalves = $saveHalves;
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
            if(!$target->makeSave($this->ability, $this->saveDC)) {
                $dispatcher->dispatch(new Event(self::EVENT_NOT_SAVED, ['caster' => $me, 'target' => $target, 'damage' => $roll]));
                return [ new TakeDamageModification($target, $roll) ];
            }
            elseif($this->saveHalves) {
                $roll = $roll->multiply(0.5);
                $dispatcher->dispatch(new Event(self::EVENT_SAVED, ['caster' => $me, 'target' => $target, 'damage' => $roll]));
                return [ new TakeDamageModification($target, $roll) ];
            }
            $dispatcher->dispatch(new Event(self::EVENT_NO_DAMAGE, ['caster' => $me, 'target' => $target]));
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
