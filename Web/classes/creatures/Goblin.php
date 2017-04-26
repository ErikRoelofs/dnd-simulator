<?php

class Goblin extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Goblin', 7,15,4, dice("1d6+2"), 2);
    }

}
