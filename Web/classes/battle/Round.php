<?php

class Round
{

    /**
     * @var Faction
     */
    protected $a;

    /**
     * @var Faction
     */
    protected $b;

    public function perform($initCounts, Faction $a, Faction $b) {
        $this->a = $a;
        $this->b = $b;

        $init = 30;
        while($init > 0) {
            if(isset($initCounts[$init])) {
                foreach($initCounts[$init] as $creature) {
                    if(!$creature->isDead()) {
                        $this->creatureTakesTurn($creature);
                    }
                }
            }
            $init--;
        }
    }

    private function creatureTakesTurn(CreatureInterface $creature) {
        if($this->a->memberOf($creature)) {
            $target = $this->b->getRandomCreature();
        }
        else {
            $target = $this->a->getRandomCreature();
        }
        if(!$target) {
            return;
        }
        $creature->makeAttack($target);
        $this->a->removeDead();
        $this->b->removeDead();
    }
}
