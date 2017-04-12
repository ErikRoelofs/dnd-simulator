<?php

class FindWeakestFriendly implements TargetingInterface
{
    public function findTarget(Perspective $perspective)
    {
        $list = $perspective->getMyFaction()->getCreatures();
        $lowestHP = INF;
        $target = null;
        foreach($list as $creature) {
            if($creature->getCurrentHP() < $lowestHP) {
                $lowestHP = $creature->getCurrentHP();
                $target = $creature;
            }
        }
        return $target;
    }

}
