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
     * @var string
     */
    protected $name;

    /**
     * ActiveEffect constructor.
     * @param ConditionInterface $condition
     * @param TerminatorInterface $terminator
     */
    public function __construct($name, ConditionInterface $condition, TerminatorInterface $terminator)
    {
        $this->condition = $condition;
        $this->terminator = $terminator;
        $terminator->setEffect($this);
        $this->name = $name;
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

    public function shouldTerminate() {
        $this->owner->loseEffect($this);
        $this->terminator->onEffectEnd();
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getName() {
        return $this->name;
    }

}
