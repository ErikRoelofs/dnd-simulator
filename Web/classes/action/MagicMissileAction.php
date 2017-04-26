<?php

class MagicMissileAction implements ActionInterface, SpellInterface
{

    protected $resource;

    /**
     * MagicMissileAction constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    private function rollDmg() {
        return mt_rand(1,4) + mt_rand(1,4) + mt_rand(1,4) + 3;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $me = $perspective->getMe();
        $log = $perspective->getLog();
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $dmg = $this->rollDmg();
            $mods[] = new TakeDamageModification($target, $dmg);
            $log->write($me->getName() . ' cast Magic Missile on ' . $target->getName() . ' for ' . $dmg . ' damage', Log::MEDIUM_IMPORTANT);
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        $slots = [];
        $slots[] = ActionInterface::TARGET_ENEMY_CREATURE;
        return $slots;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $avgDmg = 0;
            for($i = 1; $i < 50; $i++ ) {
                $damage = $this->rollDmg();
                $avgDmg = (($avgDmg * $i) + $damage ) / ( $i + 1 );
            }
            $mods[] = new TakeDamageModification($target, $avgDmg);
        }
        return $mods;
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return $this->resource->hasSlot($this->getSpellLevel());
    }

    public function getResourceCost()
    {
        return [ $this->resource ];
    }

    public function getSpellLevel()
    {
        return 1;
    }

}
