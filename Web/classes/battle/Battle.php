<?php

class Battle
{

    /**
     * @var Faction
     */
    private $factionA;

    /**
     * @var Faction
     */
    private $factionB;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    private $initCounts = [];
    private $roundCount = 0;

    /**
     * Battle constructor.
     * @param $factionA
     * @param $factionB
     */
    public function __construct(EventDispatcher $dispatcher, Faction $factionA, Faction $factionB)
    {
        $this->dispatcher = $dispatcher;
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
        $this->roundCount++;
        $round = new Round($this->dispatcher);
        $round->perform($this->initCounts, $this->factionA, $this->factionB);
    }

    public function printResult() {
        echo '<h1>Faction A survivors:</h1>';
        foreach($this->factionA->getCreatures() as $creature) {
            echo $this->getCreatureString($creature);
        }
        echo '<h1>Faction A downed:</h1>';
        foreach($this->factionA->getDowned() as $creature) {
            echo $this->getCreatureString($creature);
        }

        echo '<h1>Faction B survivors:</h1>';
        foreach($this->factionB->getCreatures() as $creature) {
            echo $this->getCreatureString($creature);
        }
        echo '<h1>Faction B downed:</h1>';
        foreach($this->factionB->getDowned() as $creature) {
            echo $this->getCreatureString($creature);
        }
        echo '<h1>' . $this->roundCount . ' round elapsed</h1>';
    }

    private function getCreatureString(CreatureInterface $creature) {
        return $creature->getName() . ' ' .$creature->getCurrentHP() . '/' . $creature->getMaxHP() . '<br />';
    }

    public function getRoundsElapsed() {
        return $this->roundCount;
    }

    public function getWinner() {
        if($this->factionB->hasCreatures()) {
            return 'faction B';
        }
        else {
            return 'faction A';
        }
    }

}
