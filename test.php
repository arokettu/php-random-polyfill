<?php

require 'vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
$rnd = new \Random\Randomizer(new \Random\Engine\Xorshift32(123));

echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;
echo $rnd->getInt(1, 1000), PHP_EOL;

echo PHP_EOL;

//try {
//    $rnd->getInt(1000, 1);
//} catch (ValueError $e) {
//    var_dump($e);
//}
//
//echo PHP_EOL;

echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
echo $rnd->getInt(), PHP_EOL;
