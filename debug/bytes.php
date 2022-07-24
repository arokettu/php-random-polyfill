<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Xorshift32(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_PHP));
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Fail());

//$rnd->getBytes(0);

$bs = 1;

echo bin2hex($rnd->getBytes($bs)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs <<= 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs <<= 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs <<= 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs <<= 1)), PHP_EOL;

echo PHP_EOL;

$bs = 3;

echo bin2hex($rnd->getBytes($bs)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs = $bs * 2 - 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs = $bs * 2 - 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs = $bs * 2 - 1)), PHP_EOL;
echo bin2hex($rnd->getBytes($bs = $bs * 2 - 1)), PHP_EOL;
