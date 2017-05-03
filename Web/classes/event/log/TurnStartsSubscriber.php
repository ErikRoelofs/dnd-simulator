<?php

class TurnStartsSubscriber implements EventSubscriberInterface
{

    /**
     * @var Log
     */
    protected $log;

    /**
     * LogRoundEndEvent constructor.
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function handle(Event $event)
    {
        $creature = $event->getData()['creature'];

        $this->log->write($creature->getName() . " is taking a turn", Log::NOTICE);

    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_START_TURN
        ];
    }

}
