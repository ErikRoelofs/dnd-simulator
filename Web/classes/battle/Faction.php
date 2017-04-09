<?php

class Faction
{

    protected $creatures = [];
    protected $corpses = [];

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
