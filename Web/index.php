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

$app['testbattle'] = function() {
    return function() {
        $log = new Log;

        $fac1 = new Faction($log);
        $fac1->addCreature(new Fighter());
        $fac1->addCreature(new Cleric());
        $fac1->addCreature(new Rogue());
        $fac1->addCreature(new Wizard());
        $fac2 = new Faction($log);

        $fac2->addCreature(new Goblin('Skiv'));
        $fac2->addCreature(new Goblin('Nork'));
        $fac2->addCreature(new Goblin('Brak'));
        $fac2->addCreature(new Goblin('Glum'));
        $fac2->addCreature(new Goblin('Tschu'));
        /*
        $fac2->addCreature(new Skeleton('Bones'));
        $fac2->addCreature(new Skeleton('Rattles'));
        $fac2->addCreature(new Skeleton('Clatters'));
        $fac2->addCreature(new Skeleton('Chatters'));
        $fac2->addCreature(new Skeleton('Jim'));

        $fac2->addCreature(new Hobgoblin('Bluk'));
        $fac2->addCreature(new Hobgoblin('Pluk'));
        $fac2->addCreature(new Hobgoblin('Ruk'));
        $fac2->addCreature(new Hobgoblin('Tuk'));
        $fac2->addCreature(new Hobgoblin('Nuk'));

        $fac2->addCreature(new Ogre("Dumfuk"));
*/

        $battle = new Battle($log, $fac1, $fac2);
        return $battle;
    };
};

$app->get('/test', function() use ($app) {

    $battle = $app['testbattle']();
    $battle->doBattle();
    $battle->printResult();

    $battle->getLog()->printOut();
    return '';

});

$app->get('/test/batch', function() use ($app) {
    $wonByA = 0;
    $wonByB = 0;
    $avgDuration = 0;
    for($i = 0 ; $i <= 5000 ; $i++ ) {
        $battle = $app['testbattle']();
        $battle->doBattle();
        if($battle->getWinner() == 'faction A') {
            $wonByA++;
        }
        else {
            $wonByB++;
        }
        $avgDuration = ($avgDuration * ($wonByB+$wonByA) + $battle->getRoundsElapsed()) / ($wonByA + $wonByB + 1);

    }

    echo round(($wonByA/$i)*100,1) . '% encounters are won by Faction A<br />';
    echo round(($wonByB/$i)*100,1) . '% encounters are won by Faction B<br />';
    echo 'average encounter duration: ' . round($avgDuration, 2);

    return '';
});

$app->run();
