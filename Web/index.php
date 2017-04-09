<?php


use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \App\User\UserProvider;

error_reporting(E_ALL);
ini_set('display_errors', true);
date_default_timezone_set("UTC");

require_once __DIR__.'/Packages/Libraries/autoload.php';
require_once __DIR__.'/services.php';
$app = new Silex\Application();
$app['debug'] = true;

$def = new ServiceDefinitions();

$def->load($app);

$app->get('/test', function() {

    $fac1 = new Faction();
    $fac1->addCreature(new Goblin());
    $fac1->addCreature(new Goblin());
    $fac2 = new Faction();
    $fac2->addCreature(new Goblin());

    $battle = new Battle($fac1, $fac2);
    $battle->doBattle();

    $battle->printResult();
    return '';

});

$app->run();
