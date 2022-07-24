<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Xorshift32(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_PHP));
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Fail());

echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;

echo PHP_EOL;

echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;

//echo PHP_EOL;

//try {
//    $rnd->getInt(1000, 1);
//} catch (ValueError $e) {
//    var_dump($e);
//}
