<?php

/**
 * Represents a complex dice expression with multiple types of dice and a flat modified (ie 2d6 + 1d8 + 4 )
 */
class DiceExpression
{
    /**
     * @var Dice[]
     */
    private $dicepool = [];

    private $flatAmount = 0;

    /**
     * DiceExpression constructor.
     * @param array $dicepool
     * @param int $flatAmount
     */
    public function __construct(array $dicepool, $flatAmount = 0)
    {
        foreach($dicepool as $die) {
            if(!$die instanceof Dice) {
                throw new InvalidArgumentException("Dicepool should contain dice.");
            }
        }
        $this->dicepool = $dicepool;
        $this->flatAmount = $flatAmount;
    }

    public function roll() {
        $rolled = 0;
        foreach($this->dicepool as $dice) {
            $rolled += $dice->roll();
        }
        $rolled += $this->flatAmount;
        return $rolled;
    }

    public function __toString() {
        $parts = [];
        foreach($this->dicepool as $dice) {
            $parts[] = (string) $dice;
        }
        $out = implode(" + ", $parts);
        if($this->flatAmount > 0) {
            $out .= ' + ' . $this->flatAmount;
        }
        elseif($this->flatAmount < 0) {
            $out .= ' - ' . ($this->flatAmount * -1);
        }
        return $out;
    }

}
