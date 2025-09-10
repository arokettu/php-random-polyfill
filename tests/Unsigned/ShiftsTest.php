<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use PHPUnit\Framework\TestCase;

use function Arokettu\Random\Unsigned\from_int;
use function Arokettu\Random\Unsigned\shift_left;
use function Arokettu\Random\Unsigned\shift_right;

final class ShiftsTest extends TestCase
{
    public function testLeftShift(): void
    {
        $num = from_int(-1, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(from_int(-1 << $i, \PHP_INT_SIZE), shift_left($num, $i));
        }
    }

    public function testRightShift(): void
    {
        $num = from_int(\PHP_INT_MAX, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(from_int(\PHP_INT_MAX >> $i, \PHP_INT_SIZE), shift_right($num, $i));
        }
    }

    public function testNonNegLeftShift(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        shift_left("\0", -1);
    }

    public function testNonNegRightShift(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        shift_right("\0", -1);
    }
}
