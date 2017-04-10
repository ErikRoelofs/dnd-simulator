<?php

class HackItStrategy implements StrategyInterface
{
    public function doTurn(CreatureInterface $creature, Faction $myFaction, Faction $otherFaction, Log $log)
    {
        $target = $otherFaction->getRandomCreature();
        if(!$target) {
            return null;
        }
        $creature->makeAttack($target, $log);
    }

}
