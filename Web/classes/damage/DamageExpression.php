<?php

class DamageExpression
{

    /**
     * @var DamageComponent[]
     */
    private $components = [];

    /**
     * DamageExpression constructor.
     * @param array $components
     */
    public function __construct(array $components)
    {
        $this->components = $components;
    }

    public function roll() {
        $results = [];
        foreach($this->components as $component) {
            $results[] = $component->roll();
        }
        return new RolledDamage($results);
    }

    public function rollDiceOnly() {
        $results = [];
        foreach($this->components as $component) {
            $results[] = $component->rollDiceOnly();
        }
        return new RolledDamage($results);
    }

    public function avg() {
        $results = [];
        foreach($this->components as $component) {
            $results[] = $component->avg();
        }
        return new RolledDamage($results);
    }

    public static function written(array $args) {
        $length = count($args);
        $parts = [];
        for($i = 0; $i < $length ;$i+=2) {
            $parts[] = new DamageComponent(dice($args[$i]), isset($args[$i+1]) ? $args[$i+1] : Damage::TYPE_NORMAL);
        }
        return new DamageExpression($parts);
    }

    public function __toString()
    {
        return implode(' + ', $this->components);
    }
}

function damage() {
    return DamageExpression::written(func_get_args());
}
