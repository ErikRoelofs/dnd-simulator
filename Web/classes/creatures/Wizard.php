<?php

class Wizard extends BaseCreature
{

   public function __construct()
    {
        parent::__construct('Zappy', 'Wizard', 8,12,4,2);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,6) +2;
    }

}
