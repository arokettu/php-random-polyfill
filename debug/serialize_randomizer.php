<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937());

$rnd->getInt();
$rnd->getInt();

$rnd2 = unserialize(serialize($rnd));

var_dump(bin2hex($rnd->getInt()), bin2hex($rnd2->getInt()));
