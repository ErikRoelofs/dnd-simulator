<?php

class SpellPoolResource implements ResourceInterface
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

    /**
     * @return mixed
     */
    public function getUses()
    {
        return $this->slots;
    }

    public function spend(ActionInterface $action, CreatureInterface $creature)
    {
        if(!$action instanceof SpellInterface) {
            throw new Exception("Cannot spend resources: this is not a spell?");
        }
        $this->slots[$action->getSpellLevel()]--;
    }

    public function getUseValue(ActionInterface $action, CreatureInterface $creature)
    {
        return $this->value;
    }

    public function getTotalValue(CreatureInterface $creature)
    {
        return $this->value * $this->uses;
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

    public function available(ActionInterface $action)
    {
        if(!$action instanceof SpellInterface) {
            throw new Exception("Cannot spend resources: this is not a spell?");
        }
        return $this->slots[$action->getSpellLevel()] > 0;
    }

    public function useSlot($level) {
        $this->slots[$level]--;
    }

}
