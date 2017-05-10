<?php

abstract class AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    private $subscribed = false;

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
    }


    abstract public function handle(Event $event);

    abstract public function getSubscribed();

    public function setEffect(ActiveEffect $effect)
    {
        $this->effect = $effect;
    }

    protected function endEffect()
    {
        $this->effect->shouldTerminate();
    }

    public function onEffectEnd() {
        if($this->subscribed) {
            $this->dispatcher->unsubscribe($this);
            $this->subscribed = false;
        }
    }

    public function onEffectStart()
    {
        if(!$this->subscribed) {
            $this->dispatcher->subscribe($this);
            $this->subscribed = true;
        }
    }

}
