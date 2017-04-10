<?php

class Fighter extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BrutalStrategy(), 'Jimbob', 'Fighter', 12,17,5,function() { return mt_rand(1,12)+3; }, -1);
    }

}
