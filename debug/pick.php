<?php

require __DIR__ . '/../vendor/autoload.php';

//$rnd = new \Random\Randomizer(new \Random\Engine\Secure());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Xorshift32(123));
$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
//$rnd = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_PHP));
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Zeros());
//$rnd = new \Random\Randomizer(new \Arokettu\Random\Tests\DevEngines\Fail());

//$rnd->pickArrayKeys([], 0);
//$rnd->pickArrayKeys([1], -1);

// [0 => 1, 9 => 10]
$a1 = range(1, 10);
// ['a' => 'aa', 'j' => 'jj']
$a2 = array_combine($a2k = range('a', 'j'), array_map(function ($v) { return $v . $v; }, $a2k));

$i = 1;

echo implode(', ', $rnd->pickArrayKeys($a1, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a1, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a1, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a1, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a1, $i++)), PHP_EOL;

echo PHP_EOL;

$i = 1;

echo implode(', ', $rnd->pickArrayKeys($a2, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a2, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a2, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a2, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a2, $i++)), PHP_EOL;

echo PHP_EOL;

$a3 = $a1 + $a2;
unset($a3[0], $a3[2], $a3[3], $a3[4], $a3[6], $a3[8], $a3['a'], $a3['c'], $a3['d'], $a3['f'], $a3['j'], $a3['d']);

$i = 1;

echo implode(', ', $rnd->pickArrayKeys($a3, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a3, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a3, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a3, $i++)), PHP_EOL;
echo implode(', ', $rnd->pickArrayKeys($a3, $i++)), PHP_EOL;

echo PHP_EOL;

$r1 = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));
$r2 = new \Random\Randomizer(new \Random\Engine\Mt19937(123, MT_RAND_MT19937));

$a = [ 'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, ];
unset($a['b']);

$b = [ 'a' => 1, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, ];

var_dump(
    $r1->pickArrayKeys($a, 1), // [ 0 => 'e' ]
    $r2->pickArrayKeys($b, 1) // [ 0 => 'd' ]
);
