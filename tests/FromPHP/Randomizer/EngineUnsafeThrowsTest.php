<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\ThrowingEngine;
use Exception;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_throws.phpt
 */
class EngineUnsafeThrowsTest extends TestCase
{
    public function testEngineThrows(): void
    {
        $randomizer = (new Randomizer(new ThrowingEngine()));

        try {
            $randomizer->getBytes(1);
        } catch (Throwable $e) {
            self::assertEquals(
                "Error",
                $e->getMessage()
            );
            self::assertEquals(Exception::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
