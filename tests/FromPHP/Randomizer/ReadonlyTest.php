<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Error;
use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Secure;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/readonly.phpt
 */
class ReadonlyTest extends TestCase
{
    public function testReadonly(): void
    {
        $one = new Randomizer(
            new PcgOneseq128XslRr64(1234)
        );

        $one_ng_clone = clone $one->engine;
        self::assertEquals($one->engine->generate(), $one_ng_clone->generate());

        try {
            $one->engine = $one_ng_clone;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testCloneSecure(): void
    {
        $two = new Randomizer(new Secure());

        try {
            $two_ng_clone = clone $two->engine;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Trying to clone an uncloneable object of class Random\Engine\Secure',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testAssign(): void
    {
        $one = new Randomizer(
            new PcgOneseq128XslRr64(1234)
        );

        $one_ng_clone = clone $one->engine;

        $two = new Randomizer(
            new Secure()
        );

        try {
            $two->engine = $one_ng_clone;
        } catch (Throwable $e) {
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
