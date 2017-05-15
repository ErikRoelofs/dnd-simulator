<?php

interface ModifyACConditionInterface extends ConditionInterface
{

    public function modifyAmount(ACCalculationInterface $ac);

}
