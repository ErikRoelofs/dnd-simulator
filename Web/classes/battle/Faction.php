<?php

class Faction
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;
    protected $creatures = [];
    protected $downed = [];

    /**
     * Faction constructor.
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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

    public function getDowned() {
        return $this->downed;
    }

    public function removeDead() {
        foreach($this->creatures as $key => $creature) {
            if($creature->isDead()) {
                $this->downed[] = $creature;
                unset($this->creatures[$key]);
                $this->dispatcher->dispatch(new Event("creature.downed", ['creature' => $creature]));
            }
        }
    }

    public function memberOf(CreatureInterface $creature) {
        foreach($this->creatures as $member) {
            if($creature === $member) {
                return true;
            }
        }
        foreach($this->downed as $member) {
            if($creature === $member) {
                return true;
            }
        }
        return false;
    }

}
