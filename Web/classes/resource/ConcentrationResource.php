<?php

class ConcentrationResource implements ResourceInterface, EventSubscriberInterface
{

    protected $owner;

    protected $used = false;

    /**
     * LimitedUseUniqueResource constructor.
     * @param $uses
     */
    public function __construct(CreatureInterface $owner)
    {
        $this->owner = $owner;
    }

    public function spend(ActionInterface $action, CreatureInterface $creature)
    {
        $this->used = true;
    }

    public function getUseValue(ActionInterface $action, CreatureInterface $creature)
    {
        return 1;
    }

    public function getTotalValue(CreatureInterface $creature)
    {
        return 1;
    }

    public function available(ActionInterface $action)
    {
        return !$this->used;
    }

    public function handle(Event $event)
    {
        if($event->getData()['creature'] === $this->owner) {
            $this->used = false;
        }
    }

    public function getSubscribed()
    {
        return [
            ConcentrationTerminator::EVENT_RELEASED
        ];
    }

}
