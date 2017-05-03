<?php

class Round
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Log
     */
    protected $log;

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
     * @param Log $log
     */
    public function __construct(EventDispatcher $dispatcher, Log $log)
    {
        $this->dispatcher = $dispatcher;
        $this->log = $log;
    }

    public function perform($initCounts, Faction $a, Faction $b) {
        $this->dispatcher->dispatch(new Event("round.start"));
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
                        $this->a->removeDead();
                        $this->b->removeDead();
                    }
                }
            }
            $init--;
        }
        $this->dispatcher->dispatch(new Event("round.end"));
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
