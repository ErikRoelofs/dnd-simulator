<?php

class SecondWindSubscriber implements EventSubscriberInterface
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
        $user = $event->getData()['user'];
        $amount = $event->getData()['amount'];

        $this->log->write($user->getName() . ' used Second Wind to restore ' . $amount. ' points of damage.', Log::MEDIUM_IMPORTANT);
    }

    public function getSubscribed()
    {
        return [
            SecondWindAction::EVENT_USED
        ];
    }

}
