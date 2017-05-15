<?php

interface ModifyRollConditionInterface extends ConditionInterface
{

    public function modifiesRoll($type, $data = null);

}
