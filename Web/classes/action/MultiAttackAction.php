<?php

class MultiAttackAction implements ActionInterface
{

    protected $attacks;

    /**
     * AttackAction constructor.
     * @param $attackBonus
     * @param $damage
     */
    public function __construct($attacks)
    {
        $this->attacks = $attacks;
    }


    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($this->attacks as $attack) {
            $temp = $this->sliceTargets($attack, $targets);
            $attackTargets = $temp['targets'];
            $targets = $temp['remainder'];
            $mods = array_merge($mods, $attack->perform($perspective, $attackTargets));
        }
        return $mods;
    }

    public function getTargetSlots()
    {
        $slots = [];
        foreach($this->attacks as $attack) {
            $slots = array_merge($slots, $attack->getTargetSlots());
        }
        return $slots;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($this->attacks as $attack) {
            $temp = $this->sliceTargets($attack, $targets);
            $attackTargets = $temp['targets'];
            $targets = $temp['remainder'];
            $mods = array_merge($mods, $attack->predict($perspective, $attackTargets));
        }
        return $mods;
    }

    public function isAvailable(CreatureInterface $creature)
    {
        return true;
    }

    public function getResourceCost()
    {
        return [];
    }

    public function sliceTargets($attack, $targets) {
        $needed = $attack->getTargetSlots();
        return [ 'targets' => array_slice($targets, 0, count($needed)), 'remainder' => array_slice($targets, count($needed)) ];
    }


}
