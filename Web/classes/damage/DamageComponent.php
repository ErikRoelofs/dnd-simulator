<?php

class DamageComponent
{

    private $type;
    /**
     * @var DiceExpression
     */
    private $dice;

    /**
     * DamageExpression constructor.
     * @param $type
     * @param DiceExpression $dice
     */
    public function __construct(DiceExpression $dice, $type = Damage::TYPE_NORMAL)
    {
        $this->type = $type;
        $this->dice = $dice;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return DiceExpression
     */
    public function getDice()
    {
        return $this->dice;
    }

    public function roll() {
        return new Damage($this->dice->roll(), $this->type);
    }

    public function avg() {
        return new Damage($this->dice->avg(), $this->type);
    }

    public function __toString() {
        return (string) $this->dice . ' ' . Damage::write($this->type);
    }

}
