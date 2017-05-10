<?php

class ConditionEffect implements EffectComponentInterface
{

    protected $fn;

    /**
     * ConditionEffect constructor.
     * @param $fn
     */
    public function __construct($fn)
    {
        $this->fn = $fn;
    }


    public function perform(Perspective $perspective, array $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            $fn = $this->fn;
            $mods[] = new GainEffectModification($target, $fn());
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        foreach($targets as $target) {
            $fn = $this->fn;
            $outcomes[] = new Outcome([new GainEffectModification($target, $fn())],1);
        }
        return new Prediction($outcomes);
    }

}
