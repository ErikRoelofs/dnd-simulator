<?php

class FindMostWoundedFriendly implements TargetingInterface
{
    public function findTarget(Perspective $perspective)
    {
        $list = $perspective->getMyFaction()->getCreatures();
        $hurtHP = 0;
        $target = null;
        foreach($list as $creature) {
            if($creature->getMaxHP() - $creature->getCurrentHP() > $hurtHP) {
                $hurtHP = $creature->getMaxHP() - $creature->getCurrentHP();
                $target = $creature;
            }
        }
        return $target;
    }

}
