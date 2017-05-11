<?php

abstract class BasePlayer extends BaseCreature
{

    protected $proficiencyBonus;

    public function __construct(StrategyInterface $strategy, $name, $type, $hp, $ac, $initiative, $proficientSaves, $abilities, $proficiencyBonus, $dispatcher)
    {
        $this->proficiencyBonus = $proficiencyBonus;
        foreach($abilities as $ability => $value) {
            $saves[$ability] = $value;
            if(isset($proficientSaves[$ability]) && $proficientSaves[$ability]) {
                $saves[$ability] += $this->proficiencyBonus;
            }
        }
        parent::__construct($strategy, $name, $type, $hp, $ac, $initiative, $saves, $abilities, $dispatcher);

    }

}
