<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\HeavilyBiasedEngine;
use PHPUnit\Framework\TestCase;
use Random\BrokenRandomEngineError;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_biased.phpt
 */
class EngineUnsafeBiased extends TestCase
{
    public function testHeavilyBiasedEngineGetInt(): void
    {
        try {
            (new Randomizer(new HeavilyBiasedEngine()))->getInt(0, 123);
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'Failed to generate an acceptable random number in 50 attempts',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testHeavilyBiasedEngineNextInt(): void
    {
        $r = (new Randomizer(new HeavilyBiasedEngine()))->nextInt();

        self::assertEquals(\PHP_INT_MAX, $r);
    }

    public function testHeavilyBiasedEngineGetBytes(): void
    {
        $r = (new Randomizer(new HeavilyBiasedEngine()))->getBytes(1);

        self::assertEquals('ff', \bin2hex($r));
    }

    public function testHeavilyBiasedEngineShuffleArray(): void
    {
        try {
            (new Randomizer(new HeavilyBiasedEngine()))->shuffleArray(\range(1, 1234));
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'Failed to generate an acceptable random number in 50 attempts',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testHeavilyBiasedEngineShuffleBytes(): void
    {
        try {
            (new Randomizer(new HeavilyBiasedEngine()))->shuffleBytes('foobar');
        } catch (Throwable $e) {
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(
                'Failed to generate an acceptable random number in 50 attempts',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
