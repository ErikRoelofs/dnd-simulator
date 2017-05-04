<?php

class RechargableResource implements ResourceInterface, EventSubscriberInterface
{

    protected $charged = true;
    protected $rechargeChance;
    protected $value;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * RechargableResource constructor.
     * @param EventDispatcher $dispatcher
     * @param $rechargeChance
     * @param $value
     */
    public function __construct(EventDispatcher $dispatcher, $rechargeChance, $value)
    {
        $this->rechargeChance = $rechargeChance;
        $this->value = $value;
        $this->dispatcher = $dispatcher;
    }

    public function spend(ActionInterface $action, CreatureInterface $creature)
    {
        $this->charged = false;
    }

    public function getUseValue(ActionInterface $action, CreatureInterface $creature)
    {
        return $this->value;
    }

    public function getTotalValue(CreatureInterface $creature)
    {
        return $this->value;
    }

    public function handle(Event $event)
    {
        if(!$this->charged && $event->getData()['creature'] == $this->effect->getOwner()) {
            if( mt_rand(1,6) >= $this->rechargeChance) {
                $this->charged = true;
            }
        }
    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_START_TURN
        ];
    }


}
