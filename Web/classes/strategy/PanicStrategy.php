<?php

class PanicStrategy implements StrategyInterface
{
    public function doTurn(CreatureInterface $creature, Faction $myFaction, Faction $otherFaction, Log $log)
    {
        if($creature->getCurrentHP() < $creature->getMaxHP()) {
            $log->write($creature->getName() . ' is injured and flees the fight!', Log::MEDIUM_IMPORTANT);
            $creature->takeDamage($creature->getCurrentHP());
            return;
        }

        $target = $otherFaction->getRandomCreature();
        if(!$target) {
            return null;
        }
        $creature->makeAttack($target, $log);
    }

}
