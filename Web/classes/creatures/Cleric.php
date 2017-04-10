<?php

class Cleric extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BrutalStrategy(), 'Dwarfy', 'Cleric', 11,18,4,function(){return mt_rand(1,8) +2;}, -1);
    }


}
