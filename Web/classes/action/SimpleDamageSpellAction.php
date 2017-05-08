<?php

class SimpleDamageSpellAction implements ActionInterface, SpellInterface
{

    const EVENT_SAVED = 'action.spell.simpledamage.passedSave';
    const EVENT_NOT_SAVED = 'action.spell.simpledamage.failedSave';

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
     * @var int
     */
    private $saveForHalf;

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
     * @param int $saveForHalf
     * @param string $name
     */
    public function __construct(SpellPoolResource $resource, DamageExpression $damageExpression, array $targets, $level, $action, $saveForHalf, $name)
    {
        $this->resource = $resource;
        $this->damageExpression = $damageExpression;
        $this->targets = $targets;
        $this->level = $level;
        $this->action = $action;
        $this->saveForHalf = $saveForHalf;
        $this->name = $name;
    }

    public function getType()
    {
        return $this->action;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $dispatcher = $perspective->getDispatcher();
        $mods = [];
        $dmg = $this->damageExpression->roll();
        $halfDmg = $dmg->multiply(0.5);
        foreach($targets as $target) {
            if(!$target) { continue; }
            if($this->saveForHalf > 0 && $target->makeSave($this->saveForHalf, $this->resource->getSaveDC())) {
                $dispatcher->dispatch(new Event(self::EVENT_SAVED, ['caster' => $me, 'target' => $target, 'damage' => $halfDmg, 'name' => $this->name]));
                $mods[] = new TakeDamageModification($target, $halfDmg);
            }
            else {
                $dispatcher->dispatch(new Event(self::EVENT_NOT_SAVED, ['caster' => $me, 'target' => $target, 'damage' => $dmg, 'name' => $this->name]));
                $mods[] = new TakeDamageModification($target, $dmg);
            }
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        return $this->targets;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        $avgDmg = $this->damageExpression->avg();
        foreach($targets as $target) {
            if($this->saveForHalf > 0) {
                $chanceOfSave = $target->predictSave($this->saveForHalf, $this->resource->getSaveDC());
                $halfDmg = $avgDmg->multiply(0.5);
                $outcomes[] = new Outcome([new TakeDamageModification($target, $avgDmg)], 1 - $chanceOfSave);
                $outcomes[] = new Outcome([new TakeDamageModification($target, $halfDmg)], $chanceOfSave);
            }
            else {
                $outcomes[] = new Outcome([new TakeDamageModification($target, $avgDmg)], 1);
            }
        }
        return new Prediction($outcomes);
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
