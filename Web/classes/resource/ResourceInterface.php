<?php

interface ResourceInterface
{
    public function spend(ActionInterface $action, CreatureInterface $creature);

    public function getUseValue();

    public function getTotalValue();
}
