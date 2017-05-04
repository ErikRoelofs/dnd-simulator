<?php

class LostConditionSubscriber implements EventSubscriberInterface
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
        $effect = $event->getData()['effect'];

        $this->log->write($creature->getName() . " lost a condition: " . get_class($effect->getCondition()), Log::MEDIUM_IMPORTANT);

    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_LOST_CONDITION
        ];
    }

}
