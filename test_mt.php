<?php

require 'vendor/autoload.php';

mt_srand(123, MT_RAND_MT19937);
$rnd = new \Random\Randomizer($e = new \Random\Engine\Mt19937(123, MT_RAND_MT19937));

//mt_srand(123, MT_RAND_PHP);
//$rnd = new \Random\Randomizer($e = new \Random\Engine\Mt19937(123, MT_RAND_PHP));

file_put_contents('test_mt_' . PHP_VERSION_ID . '.txt', var_export($e->__debugInfo(), true));

echo mt_rand(), PHP_EOL;
echo mt_rand(), PHP_EOL;
echo mt_rand(), PHP_EOL;
echo mt_rand(), PHP_EOL;
echo mt_rand(), PHP_EOL;

echo PHP_EOL;

echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;

echo PHP_EOL;

echo mt_rand(1, 1000), PHP_EOL;
echo mt_rand(1, 1000), PHP_EOL;
echo mt_rand(1, 1000), PHP_EOL;
echo mt_rand(1, 1000), PHP_EOL;
echo mt_rand(1, 1000), PHP_EOL;

echo PHP_EOL;

echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;

echo PHP_EOL;

//var_dump($e->__serialize());
//var_dump($e->__debugInfo());
//var_dump($e->__serialize()[1] == $e->__debugInfo()['__states']);

//try {
//    $rnd->getInt(1000, 1);
//} catch (ValueError $e) {
//    var_dump($e);
//}
//
//echo PHP_EOL;
