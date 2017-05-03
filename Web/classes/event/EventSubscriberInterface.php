<?php

interface EventSubscriberInterface extends EventListenerInterface
{
    public function getSubscribed();
}
