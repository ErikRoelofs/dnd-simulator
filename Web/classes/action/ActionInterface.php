<?php

interface ActionInterface
{
    const TYPE_BONUS = 1;
    const TYPE_MOVEMENT = 2;
    const TYPE_ACTION = 3;

    const TARGET_CREATURE = 100;
    const TARGET_ENEMY_CREATURE = 100;
    const TARGET_FRIENDLY_CREATURE = 101;

    public function getType();

    public function perform(Perspective $perspective, $targets);

    public function getTargetSlots();
}
