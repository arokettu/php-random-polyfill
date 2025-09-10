<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use Arokettu\Random\Unsigned\Unsigned as u;
use PHPUnit\Framework\TestCase;

final class BitsTest extends TestCase
{
    public function testIsSetBit(): void
    {
        $bits = 0b1111000011001100;
        $a = u::from_int($bits, 8);

        self::assertTrue(u::is_bit_set($a, 2));
        self::assertFalse(u::is_bit_set($a, 4));

        self::assertTrue(u::is_bit_set($a, 14));
        self::assertFalse(u::is_bit_set($a, 9));

        self::assertFalse(u::is_bit_set($a, 53));
    }

    public function testIsSetBitAllBits(): void
    {
        $bits = 0b1111111111111111;
        $a = u::from_int($bits, 2);

        for ($i = 0; $i < 16; $i++) {
            self::assertTrue(u::is_bit_set($a, $i));
        }

        $bits = 0b0000000000000000;
        $b = u::from_int($bits, 2);

        for ($i = 0; $i < 16; $i++) {
            self::assertFalse(u::is_bit_set($b, $i));
        }
    }

    public function testIsSetBitUnderflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::is_bit_set(u::from_int(0b1111000011001100, 8), -2);
    }

    public function testIsSetBitOverflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::is_bit_set(u::from_int(0b1111000011001100, 8), 100);
    }

    public function testSetBit(): void
    {
        $bits = 0b1111000011001100;
        $a = u::from_int($bits, 8);

        self::assertEquals($bits, u::to_int(u::set_bit($a, 2)));
        self::assertEquals($bits | 1 << 4, u::to_int(u::set_bit($a, 4)));

        self::assertEquals($bits, u::to_int(u::set_bit($a, 14)));
        self::assertEquals($bits | 1 << 9, u::to_int(u::set_bit($a, 9)));
    }

    public function testSetBitUnderflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::set_bit(u::from_int(0b1111000011001100, 8), -2);
    }

    public function testSetBitOverflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::set_bit(u::from_int(0b1111000011001100, 8), 100);
    }

    public function testUnsetBit(): void
    {
        $bits = 0b1111000011001100;
        $a = u::from_int($bits, 8);

        self::assertEquals($bits & ~(1 << 2), u::to_int(u::unset_bit($a, 2)));
        self::assertEquals($bits, u::to_int(u::unset_bit($a, 4)));

        self::assertEquals($bits & ~(1 << 14), u::to_int(u::unset_bit($a, 14)));
        self::assertEquals($bits, u::to_int(u::unset_bit($a, 9)));
    }

    public function testUnsetBitUnderflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::unset_bit(u::from_int(0b1111000011001100, 8), -2);
    }

    public function testUnsetBitOverflow(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        u::unset_bit(u::from_int(0b1111000011001100, 8), 100);
    }
}
