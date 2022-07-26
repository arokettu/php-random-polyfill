<?php

require __DIR__ . '/../vendor/autoload.php';

$rnd = new \Random\Randomizer();

var_dump($rnd->engine);
var_dump($rnd->something);

var_dump(isset($rnd->engine));
var_dump(isset($rnd->something));

try {
    $rnd->something = 123;
} catch (Throwable $e) {
    var_dump($e);
}
