<?php

class SimpleCreature extends BaseCreature
{
    public function __construct($name, $type, $hp, $ac, $attackBonus, $damage, $initiative, $saves, $dispatcher)
    {
        parent::__construct(new BasicStrategy([new SlayEverythingGoal(1)]), $name, $type, $hp, $ac, $attackBonus, $damage, $initiative, $saves, $dispatcher);
    }

}
