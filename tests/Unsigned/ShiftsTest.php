<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use Arokettu\Random\Unsigned\Unsigned as u;
use PHPUnit\Framework\TestCase;

final class ShiftsTest extends TestCase
{
    public function testLeftShift(): void
    {
        $num = u::from_int(-1, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(u::from_int(-1 << $i, \PHP_INT_SIZE), u::shift_left($num, $i));
        }
    }

    public function testRightShift(): void
    {
        $num = u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(u::from_int(\PHP_INT_MAX >> $i, \PHP_INT_SIZE), u::shift_right($num, $i));
        }
    }

    public function testNonNegLeftShift(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        u::shift_left("\0", -1);
    }

    public function testNonNegRightShift(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        u::shift_right("\0", -1);
    }
}
