<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes test code from the PHP Interpreter
 * @copyright 1999-2012 The PHP Group. All rights reserved.
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 */

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
final class SerializeDisallowedTest extends TestCase
{
    public function testSerializeSecure(): void
    {
        $engine = new Secure();

        $randomizer = new Randomizer($engine);
        $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX);

        try {
            \unserialize(@\serialize($randomizer));
        } catch (Throwable $e) {
            self::assertEquals(
                "Serialization of 'Random\Engine\Secure' is not allowed",
                $e->getMessage()
            );
            self::assertEquals(\Exception::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
