<?php

class Round implements EventSubscriberInterface
{

    const EVENT_START = 'round.start';
    const EVENT_END = 'round.end';
    const EVENT_INTERRUPT = 'round.interrupt';

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Faction
     */
    protected $a;

    /**
     * @var Faction
     */
    protected $b;

    /**
     * Round constructor.
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->dispatcher->subscribe($this);
    }

    public function perform($initCounts, Faction $a, Faction $b) {
        $this->dispatcher->dispatch(new Event(self::EVENT_START));
        $this->a = $a;
        $this->b = $b;

        $init = 30;
        while($init > -5) {
            if(isset($initCounts[$init])) {
                foreach($initCounts[$init] as $creature) {
                    if(!$creature->isDead()) {
                        $mods = $this->creatureTakesTurn($creature);
                        foreach($mods as $mod) {
                            $mod->execute($this->dispatcher);
                        }
                    }
                }
            }
            $init--;
        }
        $this->dispatcher->dispatch(new Event(self::EVENT_END));
        $this->dispatcher->unsubscribe($this);
    }

    private function creatureTakesTurn(CreatureInterface $creature) {

        if($this->a->memberOf($creature)) {
            return $creature->takeTurn($this->a, $this->b);
        }
        else {
            return $creature->takeTurn($this->b, $this->a);
        }
    }

    public function handle(Event $event)
    {
        $creature = $event->getData()['creature'];
        $method = $event->getData()['method'];
        if($this->a->memberOf($creature)) {
            $mods = $creature->$method($this->a, $this->b);
        }
        else {
            $mods = $creature->$method($this->b, $this->a);
        }
        foreach($mods as $mod) {
            $mod->execute($this->dispatcher);
        }
    }

    public function getSubscribed()
    {
        return [
            self::EVENT_INTERRUPT
        ];
    }


}
