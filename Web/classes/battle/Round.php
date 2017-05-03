<?php

class Round
{

    const EVENT_START = 'round.start';
    const EVENT_END = 'round.end';

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
    }

    private function creatureTakesTurn(CreatureInterface $creature) {

        if($this->a->memberOf($creature)) {
            return $creature->takeTurn($this->a, $this->b, $this->dispatcher);
        }
        else {
            return $creature->takeTurn($this->b, $this->a, $this->dispatcher);
        }
    }
}
