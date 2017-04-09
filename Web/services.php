<?php


// we still need a PROPER autoloader.
require_once __DIR__.'/classes/creatures/CreatureInterface.php';
require_once __DIR__.'/classes/creatures/BaseCreature.php';

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
		$app['yaml'] = function ($app) {
			return new \Symfony\Component\Yaml\Yaml();
		};
	}

}
