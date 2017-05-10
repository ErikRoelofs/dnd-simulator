<?php

interface ResourceInterface
{
    public function spend(ActionInterface $action, CreatureInterface $creature);

    public function getUseValue(ActionInterface $action, CreatureInterface $creature);

    public function getTotalValue(CreatureInterface $creature);

    public function available(ActionInterface $action);
}
