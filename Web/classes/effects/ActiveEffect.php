<?php

class ActiveEffect
{

    /**
     * @var CreatureInterface
     */
    protected $owner;

    /**
     * @var ConditionInterface
     */
    protected $condition;

    /**
     * @var TerminatorInterface
     */
    protected $terminator;

    /**
     * ActiveEffect constructor.
     * @param ConditionInterface $condition
     * @param TerminatorInterface $terminator
     */
    public function __construct(ConditionInterface $condition, TerminatorInterface $terminator)
    {
        $this->condition = $condition;
        $this->terminator = $terminator;
        $terminator->setEffect($this);
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return TerminatorInterface
     */
    public function getTerminator()
    {
        return $this->terminator;
    }

    public function setOwner(CreatureInterface $owner) {
        $this->owner = $owner;
    }

    public function terminate() {
        $this->owner->loseEffect($this);
    }

}
