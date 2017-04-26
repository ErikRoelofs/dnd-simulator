<?php

class Damage
{

    const TYPE_NORMAL = 1;
    const TYPE_FIRE = 2;
    const TYPE_COLD = 3;
    const TYPE_LIGHTNING = 4;

    protected $amount;
    protected $type;

    /**
     * Damage constructor.
     * @param $amount
     * @param $type
     */
    public function __construct($amount, $type)
    {
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

}
