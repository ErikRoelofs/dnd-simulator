<?php

class Event
{

    protected $name;

    protected $data;

    public function __construct($name, $data = null)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

}
