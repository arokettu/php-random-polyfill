<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use Arokettu\Random\Unsigned\Unsigned as u;
use PHPUnit\Framework\TestCase;

final class CompareTest extends TestCase
{
    public function testCompare(): void
    {
        self::assertEquals(-1, u::compare(u::from_int(0, \PHP_INT_SIZE), u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE)));
        self::assertEquals(1, u::compare(u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE), u::from_int(0, \PHP_INT_SIZE)));

        self::assertEquals(-1, u::compare(u::from_int(123456, \PHP_INT_SIZE), u::from_int(654321, \PHP_INT_SIZE)));
        self::assertEquals(1, u::compare(u::from_int(654321, \PHP_INT_SIZE), u::from_int(123456, \PHP_INT_SIZE)));

        self::assertEquals(0, u::compare(u::from_int(111111, \PHP_INT_SIZE), u::from_int(111111, \PHP_INT_SIZE)));
    }

    public function testCompareDifferentSizes(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 2 and 4 bytes given');

        u::compare(u::from_int(1, 2), u::from_int(1, 4));
    }
}
