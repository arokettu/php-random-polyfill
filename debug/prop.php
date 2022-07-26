<?php

require __DIR__ . '/../vendor/autoload.php';

$rnd = new \Random\Randomizer();

var_dump($rnd->engine);
var_dump($rnd->something);
