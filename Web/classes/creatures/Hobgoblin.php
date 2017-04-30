<?php

class Hobgoblin extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Hobgoblin', 11,18,3,damage("1d8+1"), 1, []);
    }

}
