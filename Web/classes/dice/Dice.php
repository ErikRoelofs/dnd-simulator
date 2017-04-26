<?php

/**
 * Represents a number of dice with the same number of sides (ie; 3d6)
 */
class Dice
{
    private $sides;
    private $amount;

    /**
     * Dice constructor.
     * @param $sides
     * @param $amount
     */
    public function __construct($amount, $sides)
    {
        $this->sides = $sides;
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getSides()
    {
        return $this->sides;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    public function roll() {
        $rolled = 0;
        for($i = 0; $i < $this->amount ; $i++ ) {
            $rolled += mt_rand(1,$this->sides);
        }
        return $rolled;
    }

    function __toString()
    {
        return $this->amount . "d" . $this->sides;
    }


}
