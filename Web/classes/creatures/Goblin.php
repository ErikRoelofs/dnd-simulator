<?php

class Goblin extends BaseCreature
{

   public function __construct($name, $dispatcher)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Goblin', 7,15,4, damage("1d6+2"), 2, [], $dispatcher);
    }

}
