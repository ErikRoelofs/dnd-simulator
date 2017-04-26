<?php

class Damage
{

    const TYPE_NORMAL = 1; // deprecated
    const TYPE_UNTYPED = 1;
    const TYPE_FIRE = 2;
    const TYPE_COLD = 3;
    const TYPE_LIGHTNING = 4;
    const TYPE_FORCE = 5;

    const TYPE_BLUDGEONING = 11;
    const TYPE_PIERCING = 12;
    const TYPE_SLASHING = 13;

    const STRINGS = [
        self::TYPE_UNTYPED=> 'untyped',
        self::TYPE_FIRE => 'fire',
        self::TYPE_COLD => 'cold',
        self::TYPE_LIGHTNING => 'lightning',
        self::TYPE_FORCE => 'force',
        self::TYPE_BLUDGEONING => 'bludgeoning',
        self::TYPE_SLASHING => 'slashing',
        self::TYPE_PIERCING => 'piercing',
    ];


    private $amount;
    private $type;

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

    public static function write($type) {
        return self::STRINGS[$type];
    }

    function __toString()
    {
        return $this->getAmount() . ' points of ' . self::write($this->getType());
    }


}
