<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

class EngineMt19937Test extends TestCase
{
    public function testMtRandCompare(): void
    {
        // @see https://www.php.net/manual/en/migration72.incompatible.php#migration72.incompatible.rand-mt_rand-output
        if (PHP_VERSION_ID < 70200) {
            return;
        }

        // seed, min, max
        $params = [
            [  7552,              0,           1000],
            [ -2516,          -1000,              0],
            [ 14043,              1,           1024], // pow2
            [-18246, -1 - 0x7ffffff,      0x7ffffff], // uint32 max
            [ 11673,         -12345,      0x7ffffff],
            [-12308, -1 - 0x7ffffff,              0],
        ];

        foreach ($params as [$seed, $min, $max]) {
            mt_srand($seed);
            $rnd = new Randomizer(new Mt19937($seed));

            for ($i = 0; $i < 2000; $i++) {
                self::assertEquals(mt_rand($min, $max), $rnd->getInt($min, $max), "Seed: $seed, Index: $i");
                self::assertEquals(mt_rand(), $rnd->getInt(), "Seed: $seed, Index: $i");
            }
        }
    }

    public function testMtRandBrokenCompare(): void
    {
        // seed, min, max
        $params = [
            [  7552,              0,           1000],
            [ -2516,          -1000,              0],
            [ 14043,              1,           1024], // pow2
            [-18246, -1 - 0x7ffffff,      0x7ffffff], // uint32 max
            [ 11673,         -12345,      0x7ffffff],
            [-12308, -1 - 0x7ffffff,              0],
        ];

        foreach ($params as [$seed, $min, $max]) {
            mt_srand($seed, MT_RAND_PHP);
            $rnd = new Randomizer(new Mt19937($seed, MT_RAND_PHP));

            for ($i = 0; $i < 2000; $i++) {
                self::assertEquals(mt_rand($min, $max), $rnd->getInt($min, $max), "Seed: $seed, Index: $i");
                self::assertEquals(mt_rand(), $rnd->getInt(), "Seed: $seed, Index: $i");
            }
        }
    }

    public function testSerializable(): void
    {
        mt_srand(2018239802);
        $engine = new Mt19937(2018239802);
        $rnd = new Randomizer($engine);

        for ($i = 0; $i < 100; $i++) {
            self::assertEquals(mt_rand(0, 10000), $rnd->getInt(0, 10000), "Index: $i");
        }

        $engSer = unserialize(serialize($engine)); // serialize engine

        $rnd2 = new Randomizer($engSer);

        for ($i = 0; $i < 100; $i++) {
            self::assertEquals(mt_rand(0, 10000), $rnd2->getInt(0, 10000), "Index: $i");
        }

        $rndSer = unserialize(serialize($rnd2)); // serialize entire randomizer

        for ($i = 0; $i < 100; $i++) {
            self::assertEquals(mt_rand(0, 10000), $rndSer->getInt(0, 10000), "Index: $i");
        }
    }
}
