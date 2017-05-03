<?php

class TakeDamageSubscriber implements EventSubscriberInterface
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
        $target = $event->getData()['target'];
        $dmg = $event->getData()['damage'];

        $this->log->write($target->getName() . ' has taken ' . $dmg . ' points of damage', Log::MEDIUM_IMPORTANT);
    }

    public function getSubscribed()
    {
        return [
            TakeDamageModification::EVENT_TAKE_DAMAGE
        ];
    }

}
