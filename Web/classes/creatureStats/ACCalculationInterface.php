<?php

interface ACCalculationInterface
{

    const TAG_UNARMORED = 1;
    const TAG_LIGHT_ARMOR = 2;
    const TAG_MEDIUM_ARMOR = 3;
    const TAG_HEAVY_ARMOR = 4;
    const TAG_NATURAL_ARMOR = 5;
    const TAG_HAS_SHIELD = 6;

    public function calculate();

    public function getTags();
}
