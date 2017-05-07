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
    return function($app, $factionAName, $factionBName, $log = null) {

        if($log) {
            $app['event']->subscribe(new AttackSubscriber($log));
            $app['event']->subscribe(new KnockoutSubscriber($log));
            $app['event']->subscribe(new TakeDamageSubscriber($log));
            $app['event']->subscribe(new HealDamageSubscriber($log));
            $app['event']->subscribe(new SimpleDamageSpellSubscriber($log));
            $app['event']->subscribe(new TurnStartsSubscriber($log));
            $app['event']->subscribe(new RoundStartsSubscriber($log));
            $app['event']->subscribe(new HealSpellSubscriber($log));
            $app['event']->subscribe(new SecondWindSubscriber($log));
            $app['event']->subscribe(new GainedConditionSubscriber($log));
            $app['event']->subscribe(new LostConditionSubscriber($log));
            $app['event']->subscribe(new ExplodeSubscriber($log));
            $app['event']->subscribe(new ConcentrationSubscriber($log));
        }

        $fac1 = new Faction($factionAName, $app['event']);
        $ftr = new Fighter($app['event']);
        $fac1->addCreature($ftr);
        $fac1->addCreature(new Cleric($app['event']));
        $fac1->addCreature(new Rogue($app['event']));
        $fac1->addCreature(new Wizard($app['event']));

        $fac2 = new Faction($factionBName, $app['event']);

        /*
        $fac2->addCreature(new Goblin('Skiv'));
        $fac2->addCreature(new Goblin('Nork'));
        $fac2->addCreature(new Goblin('Brak'));
        $fac2->addCreature(new Goblin('Glum'));
        $fac2->addCreature(new Goblin('Tschu'));
      */


        /*
        $fac2->addCreature(new Skeleton('Bones', $app['event']));
        $fac2->addCreature(new Skeleton('Rattles', $app['event']));
        $fac2->addCreature(new Skeleton('Clatters', $app['event']));
        $fac2->addCreature(new Skeleton('Chatters', $app['event']));
        $fac2->addCreature(new Skeleton('Jim', $app['event']));
        */

        /*
        $fac2->addCreature(new IceMephit('Chilly', $app['event']));
        $fac2->addCreature(new IceMephit('Willy', $app['event']));
        $fac2->addCreature(new IceMephit('Nilly', $app['event']));
*/
        $fac2->addCreature(new MagmaMephit('Hotty', $app['event']));
        $fac2->addCreature(new MagmaMephit('Smokey', $app['event']));
        $fac2->addCreature(new MagmaMephit('Burney', $app['event']));

/*
        $fac2->addCreature(new Hobgoblin('Bluk'));
        $fac2->addCreature(new Hobgoblin('Pluk'));
        $fac2->addCreature(new Hobgoblin('Ruk'));
        $fac2->addCreature(new Hobgoblin('Tuk'));
        $fac2->addCreature(new Hobgoblin('Nuk'));
*/
//        $fac2->addCreature(new Ogre("Dumfuk"));

        $app['event']->subscribe($fac1);
        $app['event']->subscribe($fac2);

        $battle = new Battle($app['event'], $fac1, $fac2);
        return $battle;
    };
};

$app->get('/test', function() use ($app) {
    $players = 'Players';
    $monsters = 'Monsters';
    $log = new Log;
    $battle = $app['testbattle']($app, $players, $monsters, $log);
    $battle->doBattle();
    $battle->printResult();

    $log->printOut();
    return '';

});

$app->get('/test/batch', function() use ($app) {
    $players = 'Players';
    $monsters = 'Monsters';
    $wonByPlayers = 0;
    $wonByMonsters = 0;
    $avgDuration = 0;
    for($i = 0 ; $i <= 1000 ; $i++ ) {
        $battle = $app['testbattle']($app, $players, $monsters);
        $battle->doBattle();
        if($battle->getWinner() == $players) {
            $wonByPlayers++;
        }
        else {
            $wonByMonsters++;
        }
        $avgDuration = ($avgDuration * ($wonByPlayers+$wonByMonsters) + $battle->getRoundsElapsed()) / ($wonByPlayers + $wonByMonsters + 1);

    }

    echo round(($wonByPlayers/$i)*100,1) . '% encounters are won by ' . $players . '<br />';
    echo round(($wonByMonsters/$i)*100,1) . '% encounters are won by ' . $monsters . ' <br />';
    echo 'average encounter duration: ' . round($avgDuration, 2) . ' rounds.<br />';
    echo 'Simulated a total of ' . ($i-1) . ' encounters.<br />';

    return '';
});

$app->get('/damage/test', function() use ($app) {
    damageTest(["3d6"], "3d6 untyped");
    damageTest(["3d6", Damage::TYPE_FIRE], "3d6 fire");
    damageTest(["3d6", Damage::TYPE_FIRE, "1d8", Damage::TYPE_COLD], "3d6 fire + 1d8 cold");

    return 'ok';
});

$app->get('/dice/test', function() use ($app) {
    dieTest("3d6", "3d6");
    dieTest("3d6 + 2d4", "3d6 + 2d4");
    dieTest("3d6+1d8", "3d6 + 1d8");
    dieTest("3d6 + 4", "3d6 + 4");
    dieTest("3d6+2", "3d6 + 2");
    dieTest("3d6 - 3", "3d6 - 3");
    dieTest("3d6-1", "3d6 - 1");
    return 'ok';
});

function dieTest($in, $out) {
    $expr = DiceExpression::written($in );
    if((string) $expr !== $out ) {
        throw new Exception(implode(' ', $in ) . " should convert to $out, but comes to " . $expr);
    }

}

function damageTest($in, $out) {
    $expr = DamageExpression::written($in);
    if((string) $expr !== $out ) {
        throw new Exception("$in should convert to $out, but comes to " . $expr);
    }

}

$app->run();
