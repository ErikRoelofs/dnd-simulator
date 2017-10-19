<?php

class CombinedEffect implements EffectComponentInterface
{

    protected $effects = [];

    /**
     * CombinedEffect constructor.
     * @param array $effects
     */
    public function __construct(array $effects)
    {
        foreach($effects as $effect) {
            if(!($effect instanceof EffectComponentInterface)) {
                throw new \Exception("Got something that is not an effect component.");
            }
        }
        $this->effects = $effects;
    }


    public function perform(Perspective $perspective, array $targets)
    {
        $mods = [];
        foreach($this->effects as $effect) {
            $mods = array_merge($mods, $effect->perform($perspective, $targets));
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($this->effects as $effect) {
            $mods = array_merge($mods, $effect->predict($perspective, $targets));
        }
        return $mods;
    }

}
