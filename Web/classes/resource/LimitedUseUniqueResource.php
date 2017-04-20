<?php

class LimitedUseUniqueResource implements ResourceInterface
{

    protected $uses;

    protected $value;

    /**
     * LimitedUseUniqueResource constructor.
     * @param $uses
     */
    public function __construct($uses, $value)
    {
        $this->uses = $uses;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getUses()
    {
        return $this->uses;
    }

    public function spend(ActionInterface $action, CreatureInterface $creature)
    {
        $this->uses--;
    }

    public function getUseValue()
    {
        return $this->value;
    }

    public function getTotalValue()
    {
        return $this->value * $this->uses;
    }


}
