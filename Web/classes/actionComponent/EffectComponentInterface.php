<?php

interface EffectComponentInterface
{
    public function perform(Perspective $perspective, array $targets);
    public function predict(Perspective $perspective, $targets);

}
