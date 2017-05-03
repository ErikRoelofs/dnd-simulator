<?php

class HealSpellSubscriber implements EventSubscriberInterface
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
        $healer = $event->getData()['healer'];
        $target = $event->getData()['target'];
        $amount = $event->getData()['amount'];

        $this->log->write($healer->getName() . ' cast a healing spell on ' . $target->getName() . ' for ' . $amount. ' points of damage healed.', Log::MEDIUM_IMPORTANT);
    }

    public function getSubscribed()
    {
        return [
            HealAction::EVENT_CAST
        ];
    }

}
