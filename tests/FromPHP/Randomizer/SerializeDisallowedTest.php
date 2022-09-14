<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine\Secure;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/serialize_disallowed.phpt
 */
class SerializeDisallowedTest extends TestCase
{
    public function testSerializeSecure(): void
    {
        $engine = new Secure();

        $randomizer = new Randomizer($engine);
        $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX);

        try {
            $randomizer2 = \unserialize(@\serialize($randomizer));
        } catch (Throwable $e) {
            self::assertEquals(\Exception::class, \get_class($e));
            self::assertEquals(
                "Serialization of 'Random\Engine\Secure' is not allowed",
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
