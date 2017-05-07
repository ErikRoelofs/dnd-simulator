<?php

class MonsterRepository
{

    protected $filename = 'data/monsters.yaml';

    protected $data = [];

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var NameGenerator
     */
    protected $nameGenerator;

    /**
     * MonsterRepository constructor.
     */
    public function __construct(EventDispatcher $dispatcher, NameGenerator $generator)
    {
        $this->data = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($this->filename));
        $this->dispatcher = $dispatcher;
        $this->nameGenerator = $generator;
    }

    public function getAllMonsters()
    {
        $monsters = [];
        foreach($this->data as $record) {
            $monsters[] = $this->makeSimpleMonster($record);
        }
        return $monsters;
    }

    public function getMonsterByName($name)
    {
        return $this->makeSimpleMonster($this->data[$name]);
    }

    private function makeSimpleMonster($record)
    {
        $creature = new SimpleCreature(
            $this->nameGenerator->getNameForType($record['type']),
            $record['type'],
            $record['hp'],
            $record['ac'],
            $record['attackBonus'],
            $this->makeDamage($record['damage']),
            $record['initiative'],
            $record['saves'],
            $this->dispatcher);

        if(isset($record['vulnerabilities'])) {
            foreach($record['vulnerabilities'] as $vulnerability) {
                $creature->addVulnerability($vulnerability);
            }
        }
        if(isset($record['resistances'])) {
            foreach($record['resistances'] as $resistance) {
                $creature->addResistance($resistance);
            }
        }
        if(isset($record['immunities'])) {
            foreach($record['immunities'] as $immunity) {
                $creature->addImmunity($immunity);
            }
        }
        return $creature;
    }

    private function makeDamage($damage) {
        return call_user_func_array('damage', $damage);
    }

}
