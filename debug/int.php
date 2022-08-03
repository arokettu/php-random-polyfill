<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Xorshift32(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_PHP));
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Fail());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\SingleByte());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\ThreeBytes());
//$rnd = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64('1234567890123456'));
$rnd = new \Random\Randomizer(new \Random\Engine\Xoshiro256StarStar(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Xoshiro256StarStar('12345678901234567890123456789012'));

//var_dump($rnd->engine);
//var_dump($rnd->engine->__serialize());

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

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

//var_dump($rnd->engine);
