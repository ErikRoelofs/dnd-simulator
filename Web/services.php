<?php


// we still need a PROPER autoloader.
require_once __DIR__.'/classes/creatures/CreatureInterface.php';
require_once __DIR__.'/classes/creatures/BaseCreature.php';
require_once __DIR__.'/classes/creatures/BasePlayer.php';
require_once __DIR__.'/classes/creatures/BaseMonster.php';
require_once __DIR__.'/classes/creatures/SimpleCreature.php';

require_once __DIR__.'/classes/strategy/StrategyInterface.php';
require_once __DIR__.'/classes/action/ActionInterface.php';
require_once __DIR__.'/classes/action/SpellInterface.php';
require_once __DIR__.'/classes/resource/ResourceInterface.php';
require_once __DIR__.'/classes/modification/ModificationInterface.php';
require_once __DIR__.'/classes/goal/GoalInterface.php';
require_once __DIR__.'/classes/event/EventListenerInterface.php';
require_once __DIR__.'/classes/event/EventSubscriberInterface.php';
require_once __DIR__.'/classes/terminator/TerminatorInterface.php';
require_once __DIR__.'/classes/terminator/AbstractTerminator.php';
require_once __DIR__.'/classes/actionComponent/EffectComponentInterface.php';
require_once __DIR__.'/classes/creatureStats/ACCalculationInterface.php';
require_once __DIR__.'/classes/creatureStats/AbstractACCalculation.php';

require_once __DIR__.'/classes/condition/ConditionInterface.php';
require_once __DIR__.'/classes/condition/interfaces/ModifyRollConditionInterface.php';
require_once __DIR__.'/classes/condition/interfaces/ReplaceRollConditionInterface.php';
require_once __DIR__.'/classes/condition/interfaces/RestrictActionsConditionInterface.php';
require_once __DIR__.'/classes/condition/interfaces/ModifyACConditionInterface.php';
require_once __DIR__.'/classes/condition/interfaces/StartStopConditionInterface.php';

$dir_iterator = new RecursiveDirectoryIterator(__DIR__ . "/classes");
$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

foreach ($iterator as $file) {
	if($file->getExtension() == 'php') {
		require_once $file;
	}
}

class ServiceDefinitions {

	/**
	 * ServiceDefinitions constructor.
	 */
	public function __construct() {

	}

	public function load($app)
	{

		$app['event'] = function($app) {
		    return new EventDispatcher();
        };

        $app['namegenerator'] = function($app) {
            return new NameGenerator();
        };

		$app['monster-repo'] = function($app) {
		    return new MonsterRepository($app['event'], $app['namegenerator']);
        };

		$app['encounter-builder'] = function($app) {
		    return new SimpleEncounterBuilder($app['event'], $app['monster-repo']);
        };
	}

}

