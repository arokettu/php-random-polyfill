<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use Error;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use RuntimeException;
use Throwable;

class DynamicPropertiesTest extends TestCase
{
    public function testRandomizer(): void
    {
        try {
            $r = new Randomizer();
            $r->test = 123;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot create dynamic property Random\Randomizer::$test',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSecure(): void
    {
        try {
            $engine = new Secure();
            $engine->test = 123;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot create dynamic property Random\Engine\Secure::$test',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testMt19937(): void
    {
        try {
            $engine = new Mt19937();
            $engine->test = 123;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot create dynamic property Random\Engine\Mt19937::$test',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testPcgOneseq128XslRr64(): void
    {
        try {
            $engine = new PcgOneseq128XslRr64();
            $engine->test = 123;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot create dynamic property Random\Engine\PcgOneseq128XslRr64::$test',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testXoshiro256StarStar(): void
    {
        try {
            $engine = new Xoshiro256StarStar();
            $engine->test = 123;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot create dynamic property Random\Engine\Xoshiro256StarStar::$test',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
