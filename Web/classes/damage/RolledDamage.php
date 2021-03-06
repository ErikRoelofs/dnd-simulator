<?php

class RolledDamage
{

    /**
     * @var Damage[]
     */
    private $rolls;

    /**
     * RolledDamage constructor.
     * @param $rolls
     */
    public function __construct($rolls)
    {
        $this->rolls = $rolls;
    }

    /**
     * @return Damage[]
     */
    public function getRolls()
    {
        return $this->rolls;
    }

    function __toString()
    {
        return implode(' + ', $this->rolls);
    }

    public function multiply($by) {
        $new = [];
        foreach($this->rolls as $roll) {
            $new[] = new Damage(floor($roll->getAmount() * $by), $roll->getType());
        }
        return new RolledDamage($new);
    }

    public function add(RolledDamage $damage) {
        $rolls = [];
        foreach($this->rolls as $roll) {
            $rolls[] = $roll;
        }
        foreach($damage->getRolls() as $roll) {
            $rolls[] = $roll;
        }
        return new RolledDamage($rolls);
    }

}
