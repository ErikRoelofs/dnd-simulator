<?php

class SimpleDamageSpellSubscriber implements EventSubscriberInterface
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
            case SimpleDamageSpellAction::EVENT_SAVED: {
                $this->log->write($caster->getName() . ' cast a spell at ' . $target->getName() . ' for ' . $dmg . ' damage. (They saved)', Log::MEDIUM_IMPORTANT);
                break;
            }
            case SimpleDamageSpellAction::EVENT_NOT_SAVED: {
                $this->log->write($caster->getName() . ' cast a spell at ' . $target->getName() . ' for ' . $dmg . ' damage.', Log::MEDIUM_IMPORTANT);
                break;
            }
        }
    }

    public function getSubscribed()
    {
        return [
            SimpleDamageSpellAction::EVENT_SAVED, SimpleDamageSpellAction::EVENT_NOT_SAVED
        ];
    }

}
