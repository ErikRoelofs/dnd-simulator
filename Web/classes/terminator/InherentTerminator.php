<?php

// nothing happens because this never stops, so we don't need to do anything
class InherentTerminator implements TerminatorInterface
{
    public function setEffect(ActiveEffect $effect)
    {
    }

    public function onEffectStart()
    {
    }

    public function onEffectEnd()
    {
    }

}
