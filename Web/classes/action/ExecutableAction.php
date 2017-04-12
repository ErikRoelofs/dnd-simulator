<?php

class ExecutableAction
{
    /**
     * @var ActionInterface
     */
    protected $action;

    /**
     * @var array
     */
    protected $targets;

    /**
     * ExecutableAction constructor.
     * @param ActionInterface $action
     * @param array $targets
     */
    public function __construct(ActionInterface $action, array $targets)
    {
        $this->action = $action;
        $this->targets = $targets;
    }

    /**
     * @return ActionInterface
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getTargets()
    {
        return $this->targets;
    }

}
