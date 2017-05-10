<?php

class ConcentrationTerminator extends AbstractTerminator implements TerminatorInterface, EventSubscriberInterface
{

    private $baseSaveDC = 10;

    const EVENT_HELD = 'terminator.concentration.held';
    const EVENT_BROKEN = 'terminator.concentration.broken';
    const EVENT_RELEASED = 'terminator.concentration.released';

    /**
     * @var CreatureInterface
     */
    protected $concentrator;

    /**
     * ConcentrationTerminator constructor.
     */
    public function __construct(EventDispatcher $dispatcher, CreatureInterface $concentrator)
    {
        parent::__construct($dispatcher);
        $this->concentrator = $concentrator;
    }


    public function handle(Event $event)
    {
        if($event->getName() === CreatureInterface::EVENT_TAKE_DAMAGE && $event->getData()['target'] == $this->concentrator) {
            $dc = $this->getSaveDC($event->getData()['hpLost']);
            if(!$this->concentrator->makeSave(Ability::CONSTITUTION, $dc)) {
                $this->dispatcher->dispatch(new Event(self::EVENT_BROKEN, ['creature' => $this->concentrator, 'effect' => $this->effect]));
                $this->endEffect();
            }
            else {
                $this->dispatcher->dispatch(new Event(self::EVENT_HELD, ['creature' => $this->concentrator, 'effect' => $this->effect]));
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

    public function onEffectEnd()
    {
        $this->dispatcher->dispatch(new Event(self::EVENT_RELEASED, ['creature' => $this->concentrator, 'effect' => $this->effect]));
        parent::onEffectEnd();
    }

}
