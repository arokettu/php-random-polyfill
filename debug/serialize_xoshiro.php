<?php

require __DIR__ . '/../vendor/autoload.php';

$e = new \Random\Engine\Xoshiro256StarStar(4546546);
$e->generate();
$e->generate();

var_dump($e->__serialize());
echo base64_encode(serialize($e)), PHP_EOL;

$php82serialized = 'TzozMjoiUmFuZG9tXEVuZ2luZVxYb3NoaXJvMjU2U3RhclN0YXIiOjI6e2k6MDthOjA6e31pOjE7YTo0OntpOjA7czoxNjoiZGY5Yzk3NmQ3NTYzMTFiOCI7aToxO3M6MTY6IjY5MTA5ZjFhNjc0MTNjYWYiO2k6MjtzOjE2OiJmY2JmOGZlNjc0OTQ0N2UyIjtpOjM7czoxNjoiMmJkMGE4MTk3NGZmMTk1NyI7fX0=';

$e2 = unserialize(base64_decode($php82serialized));

var_dump(bin2hex($e->generate()), bin2hex($e2->generate()));
