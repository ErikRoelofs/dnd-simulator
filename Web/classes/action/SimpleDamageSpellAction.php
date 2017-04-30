<?php

class SimpleDamageSpellAction implements ActionInterface, SpellInterface
{

    /**
     * @var SpellPoolResource
     */
    protected $resource;

    /**
     * @var DamageExpression
     */
    private $damageExpression;

    /**
     * @var array
     */
    private $targets;

    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $action;

    /**
     * @var string
     */
    private $name;

    /**
     * SimpleDamageSpellAction constructor.
     * @param SpellPoolResource $resource
     * @param DamageExpression $damageExpression
     * @param array $targets
     * @param int $level
     * @param int $action
     * @param string $name
     */
    public function __construct(SpellPoolResource $resource, DamageExpression $damageExpression, array $targets, $level, $action, $name)
    {
        $this->resource = $resource;
        $this->damageExpression = $damageExpression;
        $this->targets = $targets;
        $this->level = $level;
        $this->action = $action;
        $this->name = $name;
    }


    public function getType()
    {
        return $this->action;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $log = $perspective->getLog();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $dmg = $this->damageExpression->roll();
            $mods[] = new TakeDamageModification($target, $dmg);
            $log->write($me->getName() . ' cast ' . $this->name . ' on ' . $target->getName() . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return $this->targets;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        $avgDmg = $this->damageExpression->avg();
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
        return $this->level;
    }

}
