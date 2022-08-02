<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
$rnd = new \Random\Randomizer(new \Random\Engine\PcgOneseq128XslRr64(123));

$rnd->nextInt();
$rnd->nextInt();

echo base64_encode(serialize($rnd)), PHP_EOL;

$php82serialized = 'TzoxNzoiUmFuZG9tXFJhbmRvbWl6ZXIiOjE6e2k6MDthOjE6e3M6NjoiZW5naW5lIjtPOjMzOiJSYW5kb21cRW5naW5lXFBjZ09uZXNlcTEyOFhzbFJyNjQiOjI6e2k6MDthOjA6e31pOjE7YToyOntpOjA7czoxNjoiOGYwN2I5Y2Q0YTFlNDJjZSI7aToxO3M6MTY6ImYzYmVjNjVkMzAyMGQ0ZGUiO319fX0=';
$rnd2 = unserialize(base64_decode($php82serialized));

var_dump(bin2hex($rnd->nextInt()), bin2hex($rnd2->nextInt()));
