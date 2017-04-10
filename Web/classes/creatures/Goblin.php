<?php

class Goblin extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new BrutalStrategy(), $name, 'Goblin', 7,15,4, function() { return mt_rand(1,6)+2; }, 2);
    }

}
