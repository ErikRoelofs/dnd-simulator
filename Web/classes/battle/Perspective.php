<?php

class Perspective
{

    protected $me;
    protected $myFaction;
    protected $otherFaction;
    protected $log;

    /**
     * Perspective constructor.
     * @param $me
     * @param $myFaction
     * @param $otherFaction
     * @param $log
     */
    public function __construct($me, $myFaction, $otherFaction, $log)
    {
        $this->me = $me;
        $this->myFaction = $myFaction;
        $this->otherFaction = $otherFaction;
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function getMe()
    {
        return $this->me;
    }

    /**
     * @return mixed
     */
    public function getMyFaction()
    {
        return $this->myFaction;
    }

    /**
     * @return mixed
     */
    public function getOtherFaction()
    {
        return $this->otherFaction;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->log;
    }

}
