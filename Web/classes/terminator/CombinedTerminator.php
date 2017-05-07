<?php

class CombinedTerminator implements TerminatorInterface
{

    /**
     * @var array
     */
    protected $terminators = [];

    /**
     * CombinedTerminator constructor.
     * @param $terminators
     */
    public function __construct(array $terminators)
    {
        $this->terminators = $terminators;
    }

    public function onEffectEnd()
    {
        foreach($this->terminators as $terminator) {
            $terminator->onEffectEnd();
        }
    }

    public function setEffect(ActiveEffect $effect)
    {
        foreach($this->terminators as $terminator) {
            $terminator->setEffect($effect);
        }
    }

}
