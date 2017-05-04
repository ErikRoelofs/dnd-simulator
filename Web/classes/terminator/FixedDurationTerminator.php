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
        if($event->getName() === CreatureInterface::EVENT_END_TURN && $event->getData()['creature'] == $this->effect->getOwner()) {
            if($this->roundsLeft > 1) {
                $this->roundsLeft--;
            }
            else {
                $this->endEffect();
            }
        }
    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_END_TURN
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
