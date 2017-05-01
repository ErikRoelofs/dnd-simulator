<?php

interface ConditionInterface
{

    public function replaceRoll($type, $data = null);

    public function modifiesRoll($type, $data = null);

    public function restrictsAvailableActions();

}
