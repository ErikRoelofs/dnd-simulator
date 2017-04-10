<?php

class Goblin extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct($name, 'Goblin', 7,15,4,2);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,6)+2;
    }

}
