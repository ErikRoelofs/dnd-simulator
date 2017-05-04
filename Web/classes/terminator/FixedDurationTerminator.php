<?php

class FixedDurationTerminator extends AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    protected $roundsLeft;

    /**
     * FixedDurationTerminator constructor.
     * @param $roundsLeft
     */
    public function __construct(EventDispatcher $dispatcher, $roundsLeft)
    {
        $this->roundsLeft = $roundsLeft;
        parent::__construct($dispatcher);
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

}
