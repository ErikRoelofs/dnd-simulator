<?php

class EventDispatcher
{

    private $listenersForAll = [];
    private $listeners = [];

    public function subscribe($eventName, EventListenerInterface $listener) {
        if($eventName === "all") {
            $this->listenersForAll[] = $listener;
        }
        else {
            if (!isset($this->listeners[$eventName])) {
                $this->listeners[$eventName] = [];
            }
            $this->listeners[$eventName][] = $listener;
        }
    }

    public function dispatch(Event $event) {
        foreach($this->listenersForAll as $listener) {
            $listener->handle($event);
        }
        if(!isset($this->listeners[$event->getName()])) {
            return;
        }
        foreach($this->listeners[$event->getName()] as $listener) {
            $listener->handle($event);
        }
    }

}
