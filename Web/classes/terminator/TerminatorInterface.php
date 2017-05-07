<?php

interface TerminatorInterface
{
    public function setEffect(ActiveEffect $effect);

    public function onEffectEnd();
}
