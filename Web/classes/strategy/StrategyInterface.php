<?php

interface StrategyInterface
{
    public function doTurn(CreatureInterface $creature, Faction $myFaction, Faction $otherFaction, Log $log);
}
