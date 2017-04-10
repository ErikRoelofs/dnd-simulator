<?php

class Round
{
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

    public function perform($initCounts, Faction $a, Faction $b, Log $log) {
        $log->write('A new round starts', Log::HEADER);
        $this->a = $a;
        $this->b = $b;
        $this->log = $log;

        $init = 30;
        while($init > -5) {
            if(isset($initCounts[$init])) {
                foreach($initCounts[$init] as $creature) {
                    if(!$creature->isDead()) {
                        $this->creatureTakesTurn($creature, $log);
                    }
                }
            }
            $init--;
        }
    }

    private function creatureTakesTurn(CreatureInterface $creature) {
        $this->log->write($creature->getName() . ' is taking a turn', Log::NOTICE);

        if($this->a->memberOf($creature)) {
            $creature->takeTurn($this->a, $this->b, $this->log);
        }
        else {
            $creature->takeTurn($this->b, $this->a, $this->log);
        }
        $this->a->removeDead();
        $this->b->removeDead();
    }
}