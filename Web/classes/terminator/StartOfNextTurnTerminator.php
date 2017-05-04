<?php

class StartOfNextTurnTerminator extends AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    public function handle(Event $event)
    {
        if($event->getName() === CreatureInterface::EVENT_START_TURN && $event->getData()['creature'] == $this->effect->getOwner()) {
            $this->endEffect();
        }
    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_START_TURN
        ];
    }

}
