<?php

abstract class BaseMonster extends BaseCreature
{
    public function __construct(StrategyInterface $strategy, $name, $type, $hp, $ac, $initiative, $saves, $abilities, $dispatcher)
    {
        parent::__construct($strategy, $name, $type, $hp, $ac, $initiative, $saves, $abilities, $dispatcher);
    }

}
