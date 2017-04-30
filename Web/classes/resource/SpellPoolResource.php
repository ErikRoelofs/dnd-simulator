<?php

class SpellPoolResource implements ResourceInterface
{

    // technically you have infinite level 0 slots but whatever
    protected $slots = [ 0 => 1000000, 1 => 2, 2 => 1 ];

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
            var_dump($action);
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

}
