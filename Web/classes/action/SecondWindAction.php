<?php

class SecondWindAction implements ActionInterface
{

    const EVENT_USED = 'action.secondwind.used';

    protected $amount;
    /**
     * @var LimitedUseUniqueResource
     */
    protected $resource;

    /**
     * @var DiceExpression
     */
    private $healExpression;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct(DiceExpression $healExpr)
    {
        $this->healExpression = $healExpr;
        $this->resource = new LimitedUseUniqueResource(1, 0.5);
    }


    public function getType()
    {
        return ActionInterface::TYPE_BONUS;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $heal = $this->healExpression->roll();
            $mods[] = new HealDamageModification($target, $heal);
            $dispatcher->dispatch(new Event(self::EVENT_USED, [ 'user' => $me, 'amount' => $heal]));
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_ME];
    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        $healAvg = $this->healExpression->avg();
        foreach($targets as $target) {
            if(!$target) { continue; }
            $outcomes[] = new Outcome([new HealDamageModification($target, $healAvg)], 1);
        }
        return new Prediction($outcomes);
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return $this->resource->getUses() > 0;
    }

    public function getResourceCost()
    {
        return [ $this->resource ];
    }

}
