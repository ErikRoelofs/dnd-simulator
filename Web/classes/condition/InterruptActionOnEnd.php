<?php

class InterruptActionOnEnd implements StartStopConditionInterface
{

    protected $method;
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * InterruptActionOnEnd constructor.
     * @param $method
     * @param EventDispatcher $dispatcher
     */
    public function __construct($method, EventDispatcher $dispatcher)
    {
        $this->method = $method;
        $this->dispatcher = $dispatcher;
    }

    public function start(CreatureInterface $owner)
    {

    }

    public function stop(CreatureInterface $owner)
    {
        $this->dispatcher->dispatch(new Event(Round::EVENT_INTERRUPT, ['creature' => $owner, 'method' => $this->method]));
    }

}
