<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\shift_left;
use function Arokettu\Unsigned\shift_right;

class ShiftsTest extends TestCase
{
    public function testLeftShift()
    {
        $num = from_int(-1, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(from_int(-1 << $i, \PHP_INT_SIZE), shift_left($num, $i));
        }
    }

    public function testRightShift()
    {
        $num = from_int(\PHP_INT_MAX, \PHP_INT_SIZE);

        for ($i = 0; $i <= \PHP_INT_SIZE * 8; $i++) {
            self::assertEquals(from_int(\PHP_INT_MAX >> $i, \PHP_INT_SIZE), shift_right($num, $i));
        }
    }

    public function testNonNegLeftShift()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        shift_left("\0", -1);
    }

    public function testNonNegRightShift()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$shift must be non negative');

        shift_right("\0", -1);
    }
}
