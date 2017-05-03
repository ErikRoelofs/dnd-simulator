<?php

class FixedDurationTerminator implements TerminatorInterface, EventSubscriberInterface
{

    protected $roundsLeft;

    /**
     * @var ActiveEffect
     */
    protected $effect;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * FixedDurationTerminator constructor.
     * @param $roundsLeft
     */
    public function __construct(EventDispatcher $dispatcher, $roundsLeft)
    {
        $this->roundsLeft = $roundsLeft;
        $this->dispatcher = $dispatcher;
        $dispatcher->subscribe($this);
    }


    public function handle(Event $event)
    {
        if($event->getName() === Round::EVENT_END) {
            $this->roundsLeft--;
        }
        if($event->getName() === CreatureInterface::EVENT_START_TURN && $this->roundsLeft == 0) {
            $this->endEffect();
        }
    }

    public function getSubscribed()
    {
        return [
            Round::EVENT_END,
            CreatureInterface::EVENT_START_TURN
        ];
    }

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
