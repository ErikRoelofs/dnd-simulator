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
        return $this->rollDiceOnly() + $this->flatAmount;
    }

    public function rollDiceOnly() {
        $rolled = 0;
        foreach($this->dicepool as $dice) {
            $rolled += $dice->roll();
        }
        return $rolled;
    }

    public function avg() {
        $totalAvg = 0;
        foreach($this->dicepool as $dice) {
            $totalAvg += $dice->avg();
        }
        $totalAvg += $this->flatAmount;
        return $totalAvg;
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

    public static function written($str) {
        $pool = [];
        $flat = 0;

        if(strpos($str, '-') !== false) {
            $temp = explode('-', $str);
            $str = $temp[0];
            $flat = $temp[1] * -1;
        }

        $parts = explode("+", $str);
        foreach($parts as $part) {
            if(strpos( $part,'d') === false) {
                $flat = (int)$part;
            }
            else {
                $pool[] = self::readDie($part);
            }
        }
        return new DiceExpression($pool, $flat);
    }

    private static function readDie($die) {
        $part = trim($die);
        $subParts = explode('d', $part);
        return new Dice($subParts[0], $subParts[1]);
    }

}

function dice($str) {
    return DiceExpression::written($str);
}
