<?php

class ActionPool
{
    protected $actions = [];

    public function addAction(ActionInterface $action) {
        $this->actions[] = $action;
    }

    public function getActions() {
        return $this->actions;
    }
}
