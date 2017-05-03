<?php

class HealDamageSubscriber implements EventSubscriberInterface
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
        $amount = $event->getData()['amount'];

        $this->log->write($target->getName() . ' has healed ' . $amount . ' points of damage', Log::MEDIUM_IMPORTANT);
    }

    public function getSubscribed()
    {
        return [
            HealDamageModification::EVENT_HEAL_DAMAGE
        ];
    }

}
