<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\compare;
use function Arokettu\Unsigned\from_int;

class CompareTest extends TestCase
{
    public function testCompare()
    {
        self::assertEquals(-1, compare(from_int(0, \PHP_INT_SIZE), from_int(\PHP_INT_MAX, \PHP_INT_SIZE)));
        self::assertEquals(1, compare(from_int(\PHP_INT_MAX, \PHP_INT_SIZE), from_int(0, \PHP_INT_SIZE)));

        self::assertEquals(-1, compare(from_int(123456, \PHP_INT_SIZE), from_int(654321, \PHP_INT_SIZE)));
        self::assertEquals(1, compare(from_int(654321, \PHP_INT_SIZE), from_int(123456, \PHP_INT_SIZE)));

        self::assertEquals(0, compare(from_int(111111, \PHP_INT_SIZE), from_int(111111, \PHP_INT_SIZE)));
    }

    public function testCompareDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 2 and 4 bytes given');

        compare(from_int(1, 2), from_int(1, 4));
    }
}
