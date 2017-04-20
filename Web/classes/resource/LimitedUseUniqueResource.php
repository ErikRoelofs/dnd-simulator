<?php

class LimitedUseUniqueResource implements ResourceInterface
{

    protected $uses;

    /**
     * LimitedUseUniqueResource constructor.
     * @param $uses
     */
    public function __construct($uses)
    {
        $this->uses = $uses;
    }

    /**
     * @return mixed
     */
    public function getUses()
    {
        return $this->uses;
    }

    public function spend(ActionInterface $action)
    {
        $this->uses--;
    }

}
