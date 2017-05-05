<?php

class ExplodeSubscriber implements EventSubscriberInterface
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
        $caster = $event->getData()['caster'];
        $target = $event->getData()['target'];
        $dmg = $event->getData()['damage'];

        switch($event->getName()) {
            case ExplodeAction::EVENT_SAVED: {
                $this->log->write($caster->getName() . ' exploded all over ' . $target->getName() . ', but they saved and only took ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
                break;
            }
            case ExplodeAction::EVENT_NOT_SAVED: {
                $this->log->write($caster->getName() . ' exploded all over ' . $target->getName() . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
                break;
            }
            case ExplodeAction::EVENT_NO_DAMAGE: {
                $this->log->write($caster->getName() . ' exploded all over ' . $target->getName() . ', but it did not hurt', Log::MEDIUM_IMPORTANT);
                break;
            }
        }
    }

    public function getSubscribed()
    {
        return [
            ExplodeAction::EVENT_SAVED, ExplodeAction::EVENT_NOT_SAVED
        ];
    }

}
