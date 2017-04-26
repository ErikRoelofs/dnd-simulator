<?php

class Skeleton extends BaseCreature
{

   public function __construct($name)
    {
        parent::__construct(new DisorganisedStrategy(), $name, 'Skeleton', 13,13,4, damage("1d6+2"), 2);
        $this->vulnerabilities = [ Damage::TYPE_BLUDGEONING => true ];
    }

}
