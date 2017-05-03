<?php

class Skeleton extends BaseCreature
{

   public function __construct($name, $dispatcher)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Skeleton', 13,13,4, damage("1d6+2"), 2, [Ability::DEXTERITY => 2], $dispatcher);
        $this->vulnerabilities = [ Damage::TYPE_BLUDGEONING => true ];
    }

}
