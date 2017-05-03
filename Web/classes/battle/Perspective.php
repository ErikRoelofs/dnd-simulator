<?php

class Perspective
{

    /**
     * @var CreatureInterface
     */
    protected $me;
    /**
     * @var Faction
     */
    protected $myFaction;
    /**
     * @var Faction
     */
    protected $otherFaction;
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Perspective constructor.
     * @param CreatureInterface $me
     * @param Faction $myFaction
     * @param Faction $otherFaction
     * @param EventDispatcher $dispatcher
     */
    public function __construct(CreatureInterface $me, Faction $myFaction, Faction $otherFaction, EventDispatcher $dispatcher)
    {
        $this->me = $me;
        $this->myFaction = $myFaction;
        $this->otherFaction = $otherFaction;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return CreatureInterface
     */
    public function getMe()
    {
        return $this->me;
    }

    /**
     * @return Faction
     */
    public function getMyFaction()
    {
        return $this->myFaction;
    }

    /**
     * @return Faction
     */
    public function getOtherFaction()
    {
        return $this->otherFaction;
    }

    /**
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

}
