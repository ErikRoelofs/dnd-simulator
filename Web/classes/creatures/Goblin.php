<?php

class Goblin extends BaseCreature
{

   public function __construct()
    {
        parent::__construct($this->makeName(), 'Goblin', 7,15,4,2);
    }

    protected function doDamageRoll()
    {
        return mt_rand(1,6)+2;
    }

    private function makeName() {
        $names = ['Skiv', 'Nork', 'Brak', 'Glum', 'Tschu', 'Vaht', 'Lark', 'Kegk'];
        return $names[array_rand($names)];
    }

}
