<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use Exception;
use LogicException;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * Test Mt19937 against the current core mt_rand() function
 */
class EngineMt19937Test extends TestCase
{
    public function testMtRandCompare(): void
    {
        // @see https://www.php.net/manual/en/migration72.incompatible.php#migration72.incompatible.rand-mt_rand-output
        if (\PHP_VERSION_ID < 70200) {
            $this->markTestSkipped('PHP 7.1 has glitchy mt_rand');
        }

        // seed, min, max
        $params = [
            [  7552,              0,           1000],
            [ -2516,          -1000,              0],
            [ 14043,              1,           1024], // pow2
            [-18246, -1 - 0x7ffffff,      0x7ffffff], // uint32 max
            [ 11673,         -12345,      0x7ffffff],
            [-12308, -1 - 0x7ffffff,              0],
            [     0,              0,         366593],
        ];

        foreach ($params as [$seed, $min, $max]) {
            \mt_srand($seed);
            $rnd = new Randomizer(new Mt19937($seed));

            for ($i = 0; $i < 1000; $i++) {
                self::assertEquals(\mt_rand($min, $max), $rnd->getInt($min, $max), "Seed: $seed, Index: $i");
                self::assertEquals(\mt_rand(), $rnd->nextInt(), "Seed: $seed, Index: $i");
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
            [     0,              0,         366593],
        ];

        foreach ($params as [$seed, $min, $max]) {
            \mt_srand($seed, \MT_RAND_PHP);
            $rnd = new Randomizer(new Mt19937($seed, \MT_RAND_PHP));

            for ($i = 0; $i < 1000; $i++) {
                self::assertEquals(\mt_rand($min, $max), $rnd->getInt($min, $max), "Seed: $seed, Index: $i");
                self::assertEquals(\mt_rand(), $rnd->nextInt(), "Seed: $seed, Index: $i");
            }
        }
    }

    public function testRangeBeyondGetrandmaxBroken(): void
    {
        // test MT_RAND_PHP with mt_rand(), it seems to be consistent between versions

        // only when running 64 bit
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped("It's a 64 bit test");
        }

        $seeds = [
            -4192144863582079149 => [-266242009385968, 103353586833450],
             4459309995047605710 => [-129159038259155, 137047943910375],
             1456334543767882081 => [-192263277246342, 112522345566416],
                               0 => [-757631577129244, 844909137797168],
        ];

        foreach ($seeds as $seed => [$min, $max]) {
            if ($max - $min <= \mt_getrandmax()) {
                throw new LogicException('Invalid test params');
            }

            \mt_srand($seed, \MT_RAND_PHP);
            $rnd = new Randomizer(new Mt19937($seed, \MT_RAND_PHP));

            for ($i = 0; $i < 1000; $i++) {
                self::assertEquals(\mt_rand($min, $max), $rnd->getInt($min, $max), "Seed: $seed, Index: $i");
            }
        }
    }

    public function testSerializable(): void
    {
        \mt_srand(2018239802);
        $engine = new Mt19937(2018239802);
        $rnd = new Randomizer($engine);

        for ($i = 0; $i < 400; $i++) {
            self::assertEquals(\mt_rand(0, 10000), $rnd->getInt(0, 10000), "Index: $i");
        }

        $engSer = @\unserialize(\serialize($engine)); // serialize engine

        $rnd2 = new Randomizer($engSer);

        for ($i = 0; $i < 400; $i++) {
            self::assertEquals(\mt_rand(0, 10000), $rnd2->getInt(0, 10000), "Index: $i");
        }

        $rndSer = @\unserialize(\serialize($rnd2)); // serialize entire randomizer

        for ($i = 0; $i < 400; $i++) {
            self::assertEquals(\mt_rand(0, 10000), $rndSer->getInt(0, 10000), "Index: $i");
        }
    }

    public function testSerializableWarning(): void
    {
        if (\PHP_VERSION_ID >= 70400) {
            $this->expectNotToPerformAssertions();
        } elseif (\method_exists($this, 'expectWarning')) { // PHPUnit 8/9
            $this->expectWarning();
            $this->expectWarningMessage('Serialized object will be incompatible with PHP 8.2');
        } else {
            $this->markTestSkipped('PHPUnit is too old for this test');
        }

        \serialize(new Mt19937());
    }

    public function testUnserializeWrongArrayLength(): void
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        // seed = 123456, 3 generates
        $serialized =
            'O:21:"Random\Engine\Mt19937":2:{i:0;a:0:{}i:1;a:2:{i:0;s:8:"daefc9ab";i:1;s:8:"0e381b8d";}}';

        try {
            \unserialize($serialized);
        } catch (Throwable $e) {
            self::assertEquals(Exception::class, \get_class($e));
            self::assertEquals(
                'Invalid serialization data for Random\Engine\Mt19937 object',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testVerySpecialRange32Branch(): void
    {
        \mt_srand(123456);
        $r = new Randomizer(new Mt19937(123456));

        self::assertEquals(
            \mt_rand(-0x7fffffff - 1, 0x7fffffff),    // 32 bit PHP_INT_MIN, PHP_INT_MAX
            $r->getInt(-0x7fffffff - 1, 0x7fffffff)   // 32 bit PHP_INT_MIN, PHP_INT_MAX
        );
    }
}
