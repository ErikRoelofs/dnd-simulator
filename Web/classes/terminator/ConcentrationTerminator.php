<?php

class ConcentrationTerminator extends AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    private $baseSaveDC = 10;

    const EVENT_HELD = 'terminator.concentration.held';
    const EVENT_BROKEN = 'terminator.concentration.broken';

    /**
     * ConcentrationTerminator constructor.
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        parent::__construct($dispatcher);
    }


    public function handle(Event $event)
    {
        if($event->getName() === CreatureInterface::EVENT_TAKE_DAMAGE && $event->getData()['target'] == $this->effect->getOwner()) {
            $dc = $this->getSaveDC($event->getData()['hpLost']);
            if(!$this->effect->getOwner()->makeSave(Ability::CONSTITUTION, $dc)) {
                $this->dispatcher->dispatch(new Event(self::EVENT_BROKEN, ['creature' => $this->effect->getOwner(), 'effect' => $this->effect]));
                $this->endEffect();
            }
            else {
                $this->dispatcher->dispatch(new Event(self::EVENT_HELD, ['creature' => $this->effect->getOwner(), 'effect' => $this->effect]));
            }
        }
    }

    public function getSubscribed()
    {
        return [
            CreatureInterface::EVENT_TAKE_DAMAGE
        ];
    }

    private function getSaveDC($hpLost) {
        return max( $hpLost / 2, $this->baseSaveDC);
    }
}
