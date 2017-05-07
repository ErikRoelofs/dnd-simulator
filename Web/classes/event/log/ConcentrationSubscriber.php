<?php

class ConcentrationSubscriber implements EventSubscriberInterface
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

        if($event->getName() === ConcentrationTerminator::EVENT_BROKEN) {
            $this->log->write($creature->getName() . ' was injured and stopped concentrating on ' . $effect->getName(), Log::MEDIUM_IMPORTANT);
        }
        else {
            $this->log->write($creature->getName() . ' was injured, but continued concentrating on ' . $effect->getName(), Log::MEDIUM_IMPORTANT);
        }
    }

    public function getSubscribed()
    {
        return [
            ConcentrationTerminator::EVENT_BROKEN, ConcentrationTerminator::EVENT_HELD
        ];
    }

}
