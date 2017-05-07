<?php

abstract class AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var ActiveEffect
     */
    protected $effect;

    /**
     * FixedDurationTerminator constructor.
     * @param $roundsLeft
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $dispatcher->subscribe($this);
    }


    abstract public function handle(Event $event);

    abstract public function getSubscribed();

    public function setEffect(ActiveEffect $effect)
    {
        $this->effect = $effect;
    }

    public function endEffect()
    {
        $this->effect->terminate();
        $this->dispatcher->unsubscribe($this);
    }

}
