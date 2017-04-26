<?php

class Ogre extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Ogre', 59,11,6, damage("2d8+4"), -1);
    }

}
