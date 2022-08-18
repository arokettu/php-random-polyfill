<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;
use RuntimeException;
use Throwable;
use TypeError;
use ValueError;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/xoshiro256starstar_seed.phpt
 */
class Xoshiro256StarStarSeedTest extends TestCase
{
    public function testSeedInt(): void
    {
        $engine = new Xoshiro256StarStar(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));
        self::assertInstanceOf(Xoshiro256StarStar::class, $engine);
    }

    public function testSeedString(): void
    {
        $engine = new Xoshiro256StarStar(\random_bytes(32));
        self::assertInstanceOf(Xoshiro256StarStar::class, $engine);
    }

    public function testSeedFloat(): void
    {
        try {
            new Xoshiro256StarStar(1.0);
        } catch (Throwable $e) {
            self::assertEquals(TypeError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\Xoshiro256StarStar::__construct():' .
                ' Argument #1 ($seed) must be of type string|int|null, float given',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSeedWrongLength(): void
    {
        try {
            new Xoshiro256StarStar('foobar');
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\Xoshiro256StarStar::__construct():' .
                ' Argument #1 ($seed) must be a 32 byte (256 bit) string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSeedZeros(): void
    {
        try {
            $engine = new Xoshiro256StarStar(\str_repeat("\x00", 32));
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\Xoshiro256StarStar::__construct():' .
                ' Argument #1 ($seed) must not consist entirely of NUL bytes',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testDebugInfo(): void
    {
        $engine = new Xoshiro256StarStar(
            "\x01\x02\x03\x04\x05\x06\x07\x08" .
            "\x01\x02\x03\x04\x05\x06\x07\x08" .
            "\x01\x02\x03\x04\x05\x06\x07\x08" .
            "\x01\x02\x03\x04\x05\x06\x07\x08"
        );

        self::assertEquals([
            '__states' => [
                '0102030405060708', '0102030405060708', '0102030405060708', '0102030405060708'
            ],
        ], $engine->__debugInfo());

        for ($i = 0; $i < 1000; $i++) {
            $engine->generate();
        }

        self::assertEquals('90a025df9300cfd1', bin2hex($engine->generate()));
    }
}
