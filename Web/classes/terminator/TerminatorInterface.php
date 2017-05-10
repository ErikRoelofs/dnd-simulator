<?php

interface TerminatorInterface
{
    public function setEffect(ActiveEffect $effect);

    public function onEffectStart();

    public function onEffectEnd();
}
