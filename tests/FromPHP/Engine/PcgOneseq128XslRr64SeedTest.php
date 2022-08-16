<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use RuntimeException;
use Throwable;
use TypeError;
use ValueError;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/pcgoneseq128xslrr64_seed.phpt
 */
class PcgOneseq128XslRr64SeedTest extends TestCase
{
    public function testSeedInt(): void
    {
        $engine = new PcgOneseq128XslRr64(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));
        self::assertInstanceOf(PcgOneseq128XslRr64::class, $engine);
    }

    public function testSeedString(): void
    {
        $engine = new PcgOneseq128XslRr64(\random_bytes(16));
        self::assertInstanceOf(PcgOneseq128XslRr64::class, $engine);
    }

    public function testSeedFloat(): void
    {
        try {
            new PcgOneseq128XslRr64(1.0);
        } catch (Throwable $e) {
            self::assertEquals(TypeError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\PcgOneseq128XslRr64::__construct():' .
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
            new PcgOneseq128XslRr64('foobar');
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\PcgOneseq128XslRr64::__construct():' .
                ' Argument #1 ($seed) must be a 16 byte (128 bit) string',
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
        $engine = new PcgOneseq128XslRr64("\x01\x02\x03\x04\x05\x06\x07\x08\x01\x02\x03\x04\x05\x06\x07\x08");

        self::assertEquals([
            '__states' => [
                '7afbdfd5830d8250', 'dfc50b6959b3bafc'
            ],
        ], $engine->__debugInfo());

        for ($i = 0; $i < 1000; $i++) {
            $engine->generate();
        }

        self::assertEquals('c42016cd9005ef2e', bin2hex($engine->generate()));
    }
}
