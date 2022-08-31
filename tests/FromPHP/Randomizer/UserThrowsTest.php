<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Exception;
use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/user_throws.phpt
 */
class UserThrowsTest extends TestCase
{
    public function testEngineThrows(): void
    {
        $randomizer = (new Randomizer(
            new class () implements Engine {
                public function generate(): string
                {
                    throw new Exception('Error');
                }
            }
        ));

        try {
            $randomizer->getBytes(1);
        } catch (Throwable $e) {
            self::assertEquals(Exception::class, \get_class($e));
            self::assertEquals(
                "Error",
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
