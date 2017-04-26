<?php

class MagicMissileAction implements ActionInterface, SpellInterface
{

    protected $resource;

    /**
     * @var DiceExpression
     */
    private $diceExpression;

    /**
     * MagicMissileAction constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->diceExpression = dice("3d4+3");
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
            $dmg = $this->diceExpression->roll();
            $mods[] = new TakeDamageModification($target, $dmg);
            $log->write($me->getName() . ' cast Magic Missile on ' . $target->getName() . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        $slots = [];
        $slots[] = ActionInterface::TARGET_ENEMY_CREATURE;
        return $slots;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        $avgDmg = $this->diceExpression->avg();
        foreach($targets as $target) {
            if(!$target) { continue; }
            $mods[] = new TakeDamageModification($target, $avgDmg);
        }
        return $mods;
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return $this->resource->hasSlot($this->getSpellLevel());
    }

    public function getResourceCost()
    {
        return [ $this->resource ];
    }

    public function getSpellLevel()
    {
        return 1;
    }

}
