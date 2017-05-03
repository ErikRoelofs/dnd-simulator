<?php

class EventDispatcher
{

    private $listenersForAll = [];
    private $listeners = [];

    public function subscribe(EventSubscriberInterface $subscriber) {
        foreach($subscriber->getSubscribed() as $event) {
            $this->listen($event, $subscriber );
        }
    }

    public function unsubscribe(EventSubscriberInterface $subscriber) {
        foreach($subscriber->getSubscribed() as $event) {
            $this->stopListening($event, $subscriber );
        }
    }

    public function listen($eventName, EventListenerInterface $listener) {
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

    public function stopListening($eventName, $toCancel) {
        foreach($this->listenersForAll as $key => $listener) {
            if($listener === $toCancel) {
                unset($this->listenersForAll[$key]);
            }
        }
        foreach($this->listeners[$eventName] as $key => $listener) {
            if($listener === $toCancel) {
                unset($this->listeners[$eventName][$key]);
            }
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
