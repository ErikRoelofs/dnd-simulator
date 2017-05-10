<?php

class SpellslotResource implements ResourceInterface
{
    /**
     * @var SpellPoolResource
     */
    protected $spellPool;

    protected $level;

    /**
     * SpellslotResource constructor.
     * @param SpellPoolResource $spellPool
     * @param $level
     */
    public function __construct(SpellPoolResource $spellPool, $level)
    {
        $this->spellPool = $spellPool;
        $this->level = $level;
    }


    public function spend(ActionInterface $action, CreatureInterface $creature)
    {
        $this->spellPool->useSlot($this->level);
    }

    public function getUseValue(ActionInterface $action, CreatureInterface $creature)
    {
        return 1;
    }

    public function getTotalValue(CreatureInterface $creature)
    {
        return 1;
    }

    public function available(ActionInterface $action)
    {
        return $this->spellPool->hasSlot($this->level);
    }


}
