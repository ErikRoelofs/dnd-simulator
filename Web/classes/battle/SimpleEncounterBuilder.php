<?php

class SimpleEncounterBuilder
{

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var MonsterRepository
     */
    protected $repo;

    /**
     * SimpleEncounterBuilder constructor.
     * @param EventDispatcher $dispatcher
     * @param MonsterRepository $repo
     */
    public function __construct(EventDispatcher $dispatcher, MonsterRepository $repo)
    {
        $this->dispatcher = $dispatcher;
        $this->repo = $repo;
    }

    public function createFactionByRecipe($name, $recipe) {
        $faction = new Faction($name, $this->dispatcher);
        foreach($this->createRecipeSteps($recipe) as $step) {
            foreach($this->createMonsters($step) as $monster) {
                $faction->addCreature($monster);
            }
        }
        return $faction;
    }

    private function createRecipeSteps($recipe) {
        $steps = explode(',', $recipe);
        foreach($steps as $key => $recipe) {
            $steps[$key] = trim($recipe);
        }
        return $steps;
    }

    private function createMonsters($recipeStep) {
        $parts = explode(' ', $recipeStep);
        $monsters = [];
        for($i = 0; $i < $parts[0]; $i++ ) {
            $monsters[] = $this->repo->getMonsterByName($parts[1]);
        }
        return $monsters;
    }
}
