<?php

interface StartStopConditionInterface extends ConditionInterface
{

    public function start(CreatureInterface $owner);

    public function stop(CreatureInterface $owner);

}
