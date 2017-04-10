<?php

class HackItStrategy implements StrategyInterface
{
    public function doTurn(Perspective $perspective)
    {
        $target = $perspective->getOtherFaction()->getRandomCreature();
        if(!$target) {
            return null;
        }
        $perspective->getMe()->makeAttack($target, $perspective->getLog());
    }

}
