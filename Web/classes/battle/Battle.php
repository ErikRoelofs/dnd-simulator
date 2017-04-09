<?php

class Battle
{

    private $factionA;
    private $factionB;

    private $initCounts = [];

    /**
     * Battle constructor.
     * @param $factionA
     * @param $factionB
     */
    public function __construct(Faction $factionA, Faction $factionB)
    {
        $this->factionA = $factionA;
        $this->factionB = $factionB;
    }

    public function doBattle() {
        $rounds = 0;
        $this->setup();
        while($this->factionA->hasCreatures() && $this->factionB->hasCreatures() && $rounds < 10) {
            $this->nextRound();
            $rounds++;
        }
    }

    private function setup() {
        $this->setInitiatives($this->factionA);
        $this->setInitiatives($this->factionB);
    }

    private function setInitiatives(Faction $faction) {
        foreach($faction->getCreatures() as $creature) {
            $init = $creature->getInitiative();
            if(!isset($this->initCounts[$init])) {
                $this->initCounts[$init] = [];
            }
            $this->initCounts[$init][] = $creature;
        }
    }

    private function nextRound() {
        $round = new Round;
        $round->perform($this->initCounts, $this->factionA, $this->factionB);
    }

    public function printResult() {
        echo '<h1>Faction A survivors:</h1>';
        foreach($this->factionA->getCreatures() as $creature) {
            echo $this->getCreatureString($creature);
        }
        echo '<h1>Faction A dead:</h1>';
        foreach($this->factionA->getCorpses() as $creature) {
            echo $this->getCreatureString($creature);
        }

        echo '<h1>Faction B survivors:</h1>';
        foreach($this->factionB->getCreatures() as $creature) {
            echo $this->getCreatureString($creature);
        }
        echo '<h1>Faction B dead:</h1>';
        foreach($this->factionB->getCorpses() as $creature) {
            echo $this->getCreatureString($creature);
        }
    }

    private function getCreatureString(CreatureInterface $creature) {
        return $creature->getName() . ' ' .$creature->getCurrentHP() . '/' . $creature->getMaxHP() . '<br />';
    }

}
