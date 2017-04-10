<?php

class Rogue extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BrutalStrategy(), 'Tooky', 'Rogue', 9,14,5,function() {return mt_rand(1,6) + mt_rand(1,6) +3;}, 3);
    }

}
