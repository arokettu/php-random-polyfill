<?php

require __DIR__ . '/../vendor/autoload.php';

$rnd = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64(123));
//$rnd = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64('1234567890123456'));

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

$rnd->engine->jump(1);

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

$rnd->engine->jump(126);

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

$rnd->engine->jump(0);

echo $rnd->nextInt(), PHP_EOL;
echo $rnd->nextInt(), PHP_EOL;

echo PHP_EOL;

var_export($rnd->engine->__debugInfo());
