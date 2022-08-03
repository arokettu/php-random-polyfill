<?php

require __DIR__ . '/../vendor/autoload.php';

$rnd = new \Random\Randomizer(new \Random\Engine\Xoshiro256StarStar(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Xoshiro256StarStar('12345678901234567890123456789012'));

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

$rnd->engine->jump();

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

$rnd->engine->jumpLong();

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

var_export($rnd->engine->__debugInfo());
