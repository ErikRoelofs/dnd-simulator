<?php

interface ModificationInterface
{
    public function execute(EventDispatcher $dispatcher);
}
