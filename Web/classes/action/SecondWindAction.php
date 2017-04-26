<?php

class SecondWindAction implements ActionInterface
{

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
        $log = $perspective->getLog();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $heal = $this->healExpression->roll();
            $mods[] = new HealDamageModification($target, $heal);
            $log->write($me->getName() . ' used Second Wind to heal for ' . $heal . ' health', Log::MEDIUM_IMPORTANT);
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return [ ActionInterface::TARGET_ME];
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        $healAvg = $this->healExpression->avg();
        foreach($targets as $target) {
            if(!$target) { continue; }
            $mods[] = new HealDamageModification($target, $healAvg);
        }
        return $mods;
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
