<?php

class KnockoutSubscriber implements EventSubscriberInterface
{

    /**
     * @var Log
     */
    protected $log;

    /**
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function handle(Event $event)
    {
        $creature = $event->getData()['creature'];
        $this->log->write($creature->getName() . ' was downed!', Log::HIGH_IMPORTANT);
    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_DOWNED
        ];
    }

}
