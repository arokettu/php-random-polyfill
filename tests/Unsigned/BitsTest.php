<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\is_bit_set;
use function Arokettu\Unsigned\set_bit;
use function Arokettu\Unsigned\to_int;
use function Arokettu\Unsigned\unset_bit;

class BitsTest extends TestCase
{
    public function testIsSetBit()
    {
        $bits = 0b1111000011001100;
        $a = from_int($bits, 8);

        self::assertTrue(is_bit_set($a, 2));
        self::assertFalse(is_bit_set($a, 4));

        self::assertTrue(is_bit_set($a, 14));
        self::assertFalse(is_bit_set($a, 9));

        self::assertFalse(is_bit_set($a, 53));
    }

    public function testIsSetBitAllBits()
    {
        $bits = 0b1111111111111111;
        $a = from_int($bits, 2);

        for ($i = 0; $i < 16; $i++) {
            self::assertTrue(is_bit_set($a, $i));
        }

        $bits = 0b0000000000000000;
        $b = from_int($bits, 2);

        for ($i = 0; $i < 16; $i++) {
            self::assertFalse(is_bit_set($b, $i));
        }
    }

    public function testIsSetBitUnderflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        is_bit_set(from_int(0b1111000011001100, 8), -2);
    }

    public function testIsSetBitOverflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        is_bit_set(from_int(0b1111000011001100, 8), 100);
    }

    public function testSetBit()
    {
        $bits = 0b1111000011001100;
        $a = from_int($bits, 8);

        self::assertEquals($bits, to_int(set_bit($a, 2)));
        self::assertEquals($bits | 1 << 4, to_int(set_bit($a, 4)));

        self::assertEquals($bits, to_int(set_bit($a, 14)));
        self::assertEquals($bits | 1 << 9, to_int(set_bit($a, 9)));
    }

    public function testSetBitUnderflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        set_bit(from_int(0b1111000011001100, 8), -2);
    }

    public function testSetBitOverflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        set_bit(from_int(0b1111000011001100, 8), 100);
    }

    public function testUnsetBit()
    {
        $bits = 0b1111000011001100;
        $a = from_int($bits, 8);

        self::assertEquals($bits & ~(1 << 2), to_int(unset_bit($a, 2)));
        self::assertEquals($bits, to_int(unset_bit($a, 4)));

        self::assertEquals($bits & ~(1 << 14), to_int(unset_bit($a, 14)));
        self::assertEquals($bits, to_int(unset_bit($a, 9)));
    }

    public function testUnsetBitUnderflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        unset_bit(from_int(0b1111000011001100, 8), -2);
    }

    public function testUnsetBitOverflow()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Bit must be in range 0-63');

        unset_bit(from_int(0b1111000011001100, 8), 100);
    }
}
