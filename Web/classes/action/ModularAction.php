<?php

class ModularAction implements ActionInterface
{

    /**
     * @var TargetComponent
     */
    protected $targetComponent;

    /**
     * @var EffectComponentInterface[]
     */
    protected $effectComponents = [];

    /**
     * @var ResourceInterface[]
     */
    protected $resourceComponents = [];

    /**
     * ModularAction constructor.
     * @param TargetComponent $targetComponent
     * @param EffectComponentInterface[] $effectComponents
     * @param ResourceComponentInterface[] $resourceComponents
     */
    public function __construct(TargetComponent $targetComponent, array $effectComponents, array $resourceComponents)
    {
        $this->targetComponent = $targetComponent;
        $this->effectComponents = $effectComponents;
        $this->resourceComponents = $resourceComponents;
    }

    public function getType()
    {
        return ActionInterface::TYPE_ACTION;
    }

    public function perform(Perspective $perspective, $targets)
    {
        $mods = [];
        foreach($this->effectComponents as $effectComponent) {
            $mods = array_merge($mods, $effectComponent->perform($perspective, $targets));
        }
        return $mods;
    }

    public function predict(Perspective $perspective, $targets)
    {
        $prediction = new Prediction();
        foreach($this->effectComponents as $effectComponent) {
            $newPrediction = $effectComponent->predict($perspective, $targets);
            $prediction = new Prediction(array_merge($prediction->getOutcomes(), $newPrediction->getOutcomes()));
        }
        return $prediction;
    }

    public function getTargetSlots()
    {
        return $this->targetComponent->getTargetSlots();
    }

    public function isAvailable(CreatureInterface $creature)
    {
        $available = true;
        foreach($this->resourceComponents as $resource) {
            $available = $available && $resource->available($this);
        }
        return $available;
    }

    public function getResourceCost()
    {
        return $this->resourceComponents;
    }


}
