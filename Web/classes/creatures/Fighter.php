<?php

class Fighter extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new HackItStrategy(), 'Jimbob', 'Fighter', 12,17,5,-1);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,12)+3;
    }

}
