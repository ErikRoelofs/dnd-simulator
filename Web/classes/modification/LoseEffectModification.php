<?php

class LoseEffectModification implements ModificationInterface
{
    
    /**
     * @var CreatureInterface
     */
    protected $target;

    /**
     * @var ActiveEffect
     */
    protected $effect;

    /**
     * GainEffectModification constructor.
     * @param CreatureInterface $target
     * @param ActiveEffect $effect
     */
    public function __construct(CreatureInterface $target, ActiveEffect $effect)
    {
        $this->target = $target;
        $this->effect = $effect;
    }

    public function execute(EventDispatcher $dispatcher)
    {
        $this->target->loseEffect($this->effect);
    }

    /**
     * @return CreatureInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return ActiveEffect
     */
    public function getEffect()
    {
        return $this->effect;
    }

}
