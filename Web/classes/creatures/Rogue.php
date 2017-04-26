<?php

class Rogue extends BaseCreature
{

   public function __construct()
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), 'Tooky', 'Rogue', 9,14,5,damage("2d6+3", Damage::TYPE_SLASHING), 3);
    }

}
