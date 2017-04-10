<?php

class PanicStrategy implements StrategyInterface
{
    public function doTurn(Perspective $perspective)
    {
        if($perspective->getMe()->getCurrentHP() < $perspective->getMe()->getMaxHP()) {
            $perspective->getLog()->write($perspective->getMe()->getName() . ' is injured and flees the fight!', Log::MEDIUM_IMPORTANT);
            $perspective->getMe()->takeDamage($perspective->getMe()->getCurrentHP());
            return;
        }

        $target = $perspective->getOtherFaction()->getRandomCreature();
        if(!$target) {
            return null;
        }
        $perspective->getMe()->makeAttack($target, $perspective->getLog());
    }

}
