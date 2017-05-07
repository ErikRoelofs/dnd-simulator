<?php

class NameGenerator
{

    protected $counters = [];

    protected $names = [
        'Goblin' => [
            'Skiv',
            'Nork',
            'Brak',
            'Glum',
            'Tschu'
        ],
        'Hobgoblin' => [
            'Bluk',
            'Ruk',
            'Puk',
            'Muk',
            'Tuk'
        ],
        'Skeleton' => [
            'Chatters',
            'Rattles',
            'Clatters',
            'Jim'
        ],
        'Ogre' => [
            'Dumfuk',
            'Smallbrains',
        ]
    ];

    public function getNameForType($type) {
        if(!isset($this->names[$type])) {
            return 'Nameless';
        }

        if(!isset($this->counters[$type])) {
            $this->counters[$type] = 0;
        }
        $name = $this->names[$type][$this->counters[$type]];
        $this->counters[$type]++;
        if($this->counters[$type] >= count($this->names[$type])) {
            $this->counters[$type] = 0;
        }
        return $name;
    }

}
