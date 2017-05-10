<?php

class SpellPoolResource
{

    // technically you have infinite level 0 slots but whatever
    protected $slots = [ 0 => 1000000, 1 => 2, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0 ];

    protected $saveDC;
    protected $spellAttack;

    /**
     * SpellPoolResource constructor.
     * @param $saveDC
     * @param $spellAttack
     */
    public function __construct($saveDC, $spellAttack, $slots = null)
    {
        $this->saveDC = $saveDC;
        $this->spellAttack = $spellAttack;
        if($slots !== null) {
            $this->slots = $slots;
        }
    }

    public function hasSlot($level) {
        return isset($this->slots[$level]) && $this->slots[$level] > 0;
    }

    public function getSaveDC() {
        return $this->saveDC;
    }

    public function getSpellAttack() {
        return $this->spellAttack;
    }

    public function useSlot($level) {
        $this->slots[$level]--;
    }

}
