<?php

class Cleric extends BaseCreature
{

   public function __construct()
    {
        parent::__construct('Dwarfy', 'Cleric', 11,18,4,-1);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,8) +2;
    }

}
