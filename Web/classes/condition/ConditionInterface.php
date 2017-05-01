<?php

interface ConditionInterface
{

    public function modifiesRoll($type, $data = null);

    public function restrictsAvailableActions();

}
