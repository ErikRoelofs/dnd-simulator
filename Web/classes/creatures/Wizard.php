<?php

class Wizard extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BasicStrategy(), 'Zappy', 'Wizard', 8,12,4,function(){ return mt_rand(1,6) +2; }, 2);
    }

}
