<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Xorshift32(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_PHP));
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Fail());

echo implode(', ', $rnd->shuffleArray([1,2,3,4,5,6,7,8,9,10])), PHP_EOL;
echo implode(', ', $rnd->shuffleArray([1,2,3,4,5,6,7,8,9,10])), PHP_EOL;
echo implode(', ', $rnd->shuffleArray([1,2,3,4,5,6,7,8,9,10])), PHP_EOL;
echo implode(', ', $rnd->shuffleArray([1,2,3,4,5,6,7,8,9,10])), PHP_EOL;
echo implode(', ', $rnd->shuffleArray([1,2,3,4,5,6,7,8,9,10])), PHP_EOL;

echo PHP_EOL;

echo $rnd->shuffleBytes('1234567890'), PHP_EOL;
echo $rnd->shuffleBytes('1234567890'), PHP_EOL;
echo $rnd->shuffleBytes('1234567890'), PHP_EOL;
echo $rnd->shuffleBytes('1234567890'), PHP_EOL;
echo $rnd->shuffleBytes('1234567890'), PHP_EOL;
