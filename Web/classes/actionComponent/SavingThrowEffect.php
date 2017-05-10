<?php

class SavingThrowEffect implements EffectComponentInterface
{

    protected $dc;
    protected $type;

    /**
     * @var EffectComponentInterface
     */
    protected $onFail;

    /**
     * @var EffectComponentInterface
     */
    protected $onSucceed;

    /**
     * SavingThrowEffect constructor.
     * @param $dc
     * @param $type
     * @param EffectComponentInterface $onFail
     * @param EffectComponentInterface $onSucceed
     */
    public function __construct($dc, $type, EffectComponentInterface $onFail, EffectComponentInterface $onSucceed)
    {
        $this->dc = $dc;
        $this->type = $type;
        $this->onFail = $onFail;
        $this->onSucceed = $onSucceed;
    }

    public function perform(Perspective $perspective, array $targets)
    {
        $outs = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $saved = $target->makeSave($this->type, $this->dc);

            if( $saved ) {
                $outs = array_merge( $outs, $this->onSucceed->perform($perspective, [$target]) );
            }
            else {
                $outs = array_merge( $this->onFail->perform($perspective, [$target]) );
            }
        }
        return $outs;

    }

    public function predict(Perspective $perspective, $targets)
    {
        $outcomes = [];
        foreach($targets as $target) {
            if(!$target) { continue; }
            $chance = $target->predictSave($this->type, $this->dc);

            $mods = $this->onSucceed->predict($perspective, $targets);
            foreach($mods as $mod) {
                $outcomes[] = new Outcome([$mod], $chance);
            }
            $mods = $this->onFail->predict($perspective, $targets);
            foreach($mods as $mod) {
                $outcomes[] = new Outcome([$mod], 1 - $chance);
            }
        }
        return new Prediction($outcomes);
    }

}
