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

namespace Arokettu\Random\Tests\FromPHP\Randomizer\Methods;

use PHPUnit\Framework\TestCase;
use Random\Randomizer;
use RuntimeException;
use Throwable;
use ValueError;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/pickArrayKeys_error.phpt
 */
final class PickArrayKeysErrorTest extends TestCase
{
    public function testEmptyArray(): void
    {
        try {
            (new Randomizer())->pickArrayKeys([], 0);
        } catch (Throwable $e) {
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #1 ($array) cannot be empty',
                $e->getMessage()
            );
            self::assertEquals(ValueError::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testZero(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(\range(1, 3), 0);
        } catch (Throwable $e) {
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(ValueError::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testNeg(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(\range(1, 3), -1);
        } catch (Throwable $e) {
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(ValueError::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testTooBig(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(\range(1, 3), 10);
        } catch (Throwable $e) {
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(ValueError::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSuccess(): void
    {
        $r = (new Randomizer())->pickArrayKeys(\range(1, 3), 3);
        self::assertCount(3, $r);
        $r = (new Randomizer())->pickArrayKeys(\range(1, 3), 2);
        self::assertCount(2, $r);
    }
}
