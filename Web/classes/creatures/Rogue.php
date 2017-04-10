<?php

class Rogue extends BaseCreature
{

   public function __construct()
    {
        parent::__construct('Tooky', 'Rogue', 9,14,5,3);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,6) + mt_rand(1,6) +3;
    }

}
