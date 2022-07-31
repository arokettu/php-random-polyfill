<?php

require __DIR__ . '/../vendor/autoload.php';

$e = new \Random\Engine\PcgOneseq128XslRr64(4546546);
$e->generate();
$e->generate();

var_dump($e->__serialize());
echo base64_encode(serialize($e)), PHP_EOL;

$php82serialized = 'TzozMzoiUmFuZG9tXEVuZ2luZVxQY2dPbmVzZXExMjhYc2xScjY0IjoyOntpOjA7YTowOnt9aToxO2E6Mjp7aTowO3M6MTY6IjQyY2JkMzI1OGZjMDdiMmYiO2k6MTtzOjE2OiI0ZTQxZDFlNjBhYzY1NTk5Ijt9fQ==';

$e2 = unserialize(base64_decode($php82serialized));

var_dump(bin2hex($e->generate()), bin2hex($e2->generate()));
