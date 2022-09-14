<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\EmptyStringEngine;
use PHPUnit\Framework\TestCase;
use Random\BrokenRandomEngineError;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_empty_string.phpt
 */
class EngineUnsafeEmptyStringTest extends TestCase
{
    public function testEmptyStringEngineGetInt(): void
    {
        try {
            (new Randomizer(new EmptyStringEngine()))->getInt(0, 123);
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'A random engine must return a non-empty string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testEmptyStringEngineNextInt(): void
    {
        try {
            (new Randomizer(new EmptyStringEngine()))->nextInt();
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'A random engine must return a non-empty string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testEmptyStringEngineGetBytes(): void
    {
        try {
            (new Randomizer(new EmptyStringEngine()))->getBytes(1);
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'A random engine must return a non-empty string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testEmptyStringEngineShuffleArray(): void
    {
        try {
            (new Randomizer(new EmptyStringEngine()))->shuffleArray(\range(1, 1234));
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'A random engine must return a non-empty string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testEmptyStringEngineShuffleBytes(): void
    {
        try {
            (new Randomizer(new EmptyStringEngine()))->shuffleBytes('foobar');
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'A random engine must return a non-empty string',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

}
