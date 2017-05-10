<?php

class TargetComponent
{

    protected $slots;

    /**
     * TargetComponent constructor.
     * @param $slots
     */
    public function __construct($slots)
    {
        $this->slots = $slots;
    }


    public function getTargetSlots() {
        return $this->slots;
    }
}
