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
                        $mods = $this->creatureTakesTurn($creature, $log);
                        foreach($mods as $mod) {
                            $mod->execute($log);
                        }
                        $this->a->removeDead();
                        $this->b->removeDead();
                    }
                }
            }
            $init--;
        }
    }

    private function creatureTakesTurn(CreatureInterface $creature) {
        $this->log->write($creature->getName() . ' is taking a turn', Log::NOTICE);

        if($this->a->memberOf($creature)) {
            return $creature->takeTurn($this->a, $this->b, $this->log);
        }
        else {
            return $creature->takeTurn($this->b, $this->a, $this->log);
        }
    }
}
