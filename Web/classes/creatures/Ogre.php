<?php

class Ogre extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Ogre', 59,11,6, function() { return mt_rand(1,8)+mt_rand(1,8)+4; }, -1);
    }

}
