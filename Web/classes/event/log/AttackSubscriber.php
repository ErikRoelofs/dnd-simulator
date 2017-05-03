<?php

class AttackSubscriber implements EventSubscriberInterface
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
        $attacker = $event->getData()['attacker'];
        $target = $event->getData()['target'];
        $dmg = $event->getData()['damage'];

        switch($event->getName()) {
            case AttackAction::EVENT_MISS: {
                $this->log->write($attacker->getName() . ' missed ' . $target->getName(), Log::MEDIUM_IMPORTANT);
                break;
            }
            case AttackAction::EVENT_HIT: {
                $this->log->write($attacker->getName() . ' hit ' . $target->getName() . ' for ' . $dmg . ' damage.', Log::MEDIUM_IMPORTANT);
                break;
            }
            case AttackAction::EVENT_CRIT: {
                $this->log->write($attacker->getName() . ' critically hit ' . $target->getName() . ' for ' . $dmg . ' damage.', Log::MEDIUM_IMPORTANT);
                break;
            }
        }
    }

    public function getSubscribed()
    {
        return [
            AttackAction::EVENT_MISS,AttackAction::EVENT_HIT, AttackAction::EVENT_CRIT,
        ];
    }

}
