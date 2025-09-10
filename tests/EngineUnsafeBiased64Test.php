<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use Arokettu\Random\Tests\DevEngines\FromPHP\HeavilyBiasedEngine;
use PHPUnit\Framework\TestCase;
use Random\BrokenRandomEngineError;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * Modified to test 64 branch
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_biased.phpt
 */
class EngineUnsafeBiased64Test extends TestCase
{
    public function testHeavilyBiasedEngineGetInt(): void
    {
        // only when running 64 bit
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped("It's a 64 bit test");
        }

        try {
            (new Randomizer(new HeavilyBiasedEngine()))->getInt(0, 1234567890123456789); // somewhat close to 2^60
        } catch (Throwable $e) {
            self::assertEquals(
                'Failed to generate an acceptable random number in 50 attempts',
                $e->getMessage()
            );
            self::assertEquals(BrokenRandomEngineError::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
