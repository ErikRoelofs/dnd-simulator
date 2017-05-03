<?php

class LogListener implements EventListenerInterface
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
        $this->log->write("I just received a: " . $event->getName() . " event.", Log::LOW_IMPORTANT);
    }

}
