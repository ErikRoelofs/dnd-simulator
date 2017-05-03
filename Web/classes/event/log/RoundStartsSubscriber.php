<?php

class RoundStartsSubscriber implements EventSubscriberInterface
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
        $this->log->write("A new round is beginning.", Log::HEADER);

    }

    public function getSubscribed()
    {
        return [
            Round::EVENT_START
        ];
    }

}
