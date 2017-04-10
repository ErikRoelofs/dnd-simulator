<?php

class Faction
{

    /**
     * @var Log
     */
    protected $log;
    protected $creatures = [];
    protected $corpses = [];

    /**
     * Faction constructor.
     * @param $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function addCreature(CreatureInterface $creature) {
        $this->creatures[] = $creature;
    }

    public function getRandomCreature() {
        if(!count($this->creatures)) {
            return null;
        }
        return $this->creatures[array_rand($this->creatures)];
    }

    public function hasCreatures() {
        return count($this->creatures)>0;
    }

    public function getCreatures() {
        return $this->creatures;
    }

    public function getCorpses() {
        return $this->corpses;
    }

    public function removeDead() {
        foreach($this->creatures as $key => $creature) {
            if($creature->isDead()) {
                $this->corpses[] = $creature;
                unset($this->creatures[$key]);
                $this->log->write($creature->getName() . ' has been downed.', Log::HIGH_IMPORTANT);
            }
        }
    }

    public function memberOf(CreatureInterface $creature) {
        foreach($this->creatures as $member) {
            if($creature === $member) {
                return true;
            }
        }
        foreach($this->corpses as $member) {
            if($creature === $member) {
                return true;
            }
        }
        return false;
    }

}
