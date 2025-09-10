<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use Arokettu\Random\Unsigned\Unsigned as u;
use PHPUnit\Framework\TestCase;

final class ArithmeticIntTest extends TestCase
{
    public function testSum(): void
    {
        // normal
        self::assertEquals(
            123456 + 654321,
            u::to_int(u::add_int(u::from_int(123456, \PHP_INT_SIZE), 654321))
        );
        //overflow
        self::assertEquals(
            (123 + 234) & 255,
            u::to_int(u::add_int(u::from_int(123, 1), 234))
        );
        // zero
        self::assertEquals(
            123456,
            u::to_int(u::add_int(u::from_int(123456, \PHP_INT_SIZE), 0))
        );
        // negative
        self::assertEquals(
            123456 - 456,
            u::to_int(u::add_int(u::from_int(123456, \PHP_INT_SIZE), -456))
        );
        // int overflow
        self::assertEquals(
            u::from_int(-2, \PHP_INT_SIZE),
            u::add_int(u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE), \PHP_INT_MAX)
        );
    }

    public function testAddOverlfow64(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxAdd = 9223372036854775552;
        $overflow = 9223372036854775553;

        self::assertEquals(
            '7fffffffffffffff',
            u::to_hex(u::add_int(u::from_hex("ff", 8), $maxAdd))
        );
        self::assertEquals(
            '800000000000feff',
            u::to_hex(u::add_int(u::from_hex("ffff", 8), $maxAdd))
        );
        self::assertEquals(
            '8000000000000000',
            u::to_hex(u::add_int(u::from_hex("ff", 8), $overflow))
        );
        self::assertEquals(
            '800000000000ff00',
            u::to_hex(u::add_int(u::from_hex("ffff", 8), $overflow))
        );
    }

    public function testAddOverlfow32(): void
    {
        $maxAdd = 2147483392;
        $overflow = 2147483393;

        self::assertEquals(
            '7fffffff',
            u::to_hex(u::add_int(u::from_hex("ff", 4), $maxAdd))
        );
        self::assertEquals(
            '8000feff',
            u::to_hex(u::add_int(u::from_hex("ffff", 4), $maxAdd))
        );
        self::assertEquals(
            '80000000',
            u::to_hex(u::add_int(u::from_hex("ff", 4), $overflow))
        );
        self::assertEquals(
            '8000ff00',
            u::to_hex(u::add_int(u::from_hex("ffff", 4), $overflow))
        );
    }

    public function testSub(): void
    {
        // normal
        self::assertEquals(
            654321 - 123456,
            u::to_int(u::sub_int(u::from_int(654321, \PHP_INT_SIZE), 123456))
        );
        // overflow
        self::assertEquals(
            (123456 - 654321) & \PHP_INT_MAX >> 7,
            u::to_int(u::sub_int(u::from_int(123456, \PHP_INT_SIZE - 1), 654321))
        );
        // special
        self::assertEquals(
            123456, // zeros if sign is truncated
            u::to_int(u::sub_int(u::from_int(123456, \PHP_INT_SIZE - 1), \PHP_INT_MIN))
        );
    }

    public function testSubRev(): void
    {
        // normal
        self::assertEquals(
            654321 - 123456,
            u::to_int(u::sub_int_rev(654321, u::from_int(123456, \PHP_INT_SIZE)))
        );
        // overflow
        self::assertEquals(
            (123456 - 654321) & \PHP_INT_MAX >> 7,
            u::to_int(u::sub_int_rev(123456, u::from_int(654321, \PHP_INT_SIZE - 1)))
        );
        // not special but check anyway
        self::assertEquals(
            \PHP_INT_MAX - 123455, // overflow
            u::to_int(u::sub_int_rev(\PHP_INT_MIN, u::from_int(123456, \PHP_INT_SIZE)))
        );
    }

    public function testMul(): void
    {
        // normal
        self::assertEquals(
            11111 * 11111,
            u::to_int(u::mul_int(u::from_int(11111, \PHP_INT_SIZE), 11111))
        );
        // overflow
        self::assertEquals(
            (11111 * 11111) & 65535,
            u::to_int(u::mul_int(u::from_int(11111, 2), 11111))
        );
        // 0
        self::assertEquals(
            0,
            u::to_int(u::mul_int(u::from_int(11111, 2), 0))
        );
        // 1
        self::assertEquals(
            11111,
            u::to_int(u::mul_int(u::from_int(11111, 2), 1))
        );
        // -1
        self::assertEquals(
            u::from_int(-11111, 2),
            u::mul_int(u::from_int(11111, 2), -1)
        );
        // negative
        self::assertEquals(
            u::from_int(-11111 * 11111, 2),
            u::mul_int(u::from_int(11111, 2), -11111)
        );
        // special case
        self::assertEquals(
            0, // multiplying by even number will carry sign beyond overflow
            u::to_int(u::mul_int(u::from_int(11110, \PHP_INT_SIZE), \PHP_INT_MIN))
        );
        // int overflow
        self::assertEquals(
            65413,
            u::to_int(u::mul_int(u::from_int(123, 2), \PHP_INT_MAX))
        );
    }

    public function testMulOverflow64(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxMul = 0x80000000000000;
        $overflow = 0x80000000000001;

        self::assertEquals(
            '007fffffffffffffff80000000000000',
            u::to_hex(u::mul_int(u::from_hex("ffffffffffffffff", 16), $maxMul))
        );
        self::assertEquals(
            '0080000000000000ff7fffffffffffff',
            u::to_hex(u::mul_int(u::from_hex("ffffffffffffffff", 16), $overflow))
        );
    }

    public function testMulOverflow32(): void
    {
        $maxMul = 0x800000;
        $overflow = 0x800001;

        self::assertEquals(
            '007fffffff800000',
            u::to_hex(u::mul_int(u::from_hex("ffffffff", 8), $maxMul))
        );
        self::assertEquals(
            '00800000ff7fffff',
            u::to_hex(u::mul_int(u::from_hex("ffffffff", 8), $overflow))
        );
    }

    public function testDiv(): void
    {
        self::assertEquals(\intdiv(123456, 1000), u::to_int(u::div_int(u::from_int(123456, 8), 1000)));
        self::assertEquals(\intdiv(123456, 1), u::to_int(u::div_int(u::from_int(123456, 8), 1)));
        self::assertEquals(\intdiv(123456, 1024), u::to_int(u::div_int(u::from_int(123456, 8), 1024)));
        self::assertEquals(\intdiv(123456, 654321), u::to_int(u::div_int(u::from_int(123456, 8), 654321)));
        self::assertEquals(\intdiv(123456, 123456), u::to_int(u::div_int(u::from_int(123456, 8), 123456)));
    }

    public function testDivNoZero(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Division by zero');

        u::div_int(u::from_int(123456, 8), 0);
    }

    public function testDivNoNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use div($a, from_int($b)) for unsigned logic');

        u::div_int(u::from_int(123456, 8), -2);
    }

    public function testMod(): void
    {
        self::assertEquals(123456 % 1000, u::mod_int(u::from_int(123456, 8), 1000));
        self::assertEquals(123456 % 1, u::mod_int(u::from_int(123456, 8), 1));
        self::assertEquals(123456 % 1024, u::mod_int(u::from_int(123456, 8), 1024));
        self::assertEquals(123456 % 654321, u::mod_int(u::from_int(123456, 8), 654321));
        self::assertEquals(123456 % 123456, u::mod_int(u::from_int(123456, 8), 123456));

        // big pow2
        self::assertEquals(
            0xeeff00,
            u::mod_int(u::from_hex('112233445566778899aabbccddeeff00', 16), 2 ** 24)
        );
    }

    public function testModOverflow64(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxDiv = 0x0ffffffffffffff;
        // skip pow2
        $overflow = 0x100000000000001;

        self::assertEquals(
            255,
            u::mod_int(u::from_hex('ffffffffffffffff', 8), $maxDiv)
        );
        self::assertEquals(
            72057594037927680,
            u::mod_int(u::from_hex('ffffffffffffffff', 8), $overflow)
        );
    }

    public function testDivMod(): void
    {
        self::assertEquals(
            (u::div_mod_int(u::from_int(123456, 8), 1000))[1],
            u::mod_int(u::from_int(123456, 8), 1000)
        );
        self::assertEquals(
            (u::div_mod_int(u::from_int(123456, 8), 1))[1],
            u::mod_int(u::from_int(123456, 8), 1)
        );
        self::assertEquals(
            (u::div_mod_int(u::from_int(123456, 8), 1024))[1],
            u::mod_int(u::from_int(123456, 8), 1024)
        );
        self::assertEquals(
            (u::div_mod_int(u::from_int(123456, 8), 654321))[1],
            u::mod_int(u::from_int(123456, 8), 654321)
        );
        self::assertEquals(
            (u::div_mod_int(u::from_int(123456, 8), 123456))[1],
            u::mod_int(u::from_int(123456, 8), 123456)
        );
        // big pow2
        self::assertEquals(
            [\strrev(\hex2bin('000000112233445566778899aabbccdd')), 0xeeff00],
            u::div_mod_int(u::from_hex('112233445566778899aabbccddeeff00', 16), 2 ** 24)
        );
    }

    public function testDivModNoNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use div_mod($a, from_int($b)) for unsigned logic');

        u::div_mod_int(u::from_int(123456, 8), -2);
    }

    public function testDivModFromUuid(): void
    {
        $a = u::from_hex('200000000000011', 8);
        $b = 10000000;

        list($d, $m) = (u::div_mod_int($a, $b));

        self::assertEquals('14411518807', u::to_dec($d));
        self::assertEquals(5855889, $m);

        // also check simple mod
        self::assertEquals(5855889, u::mod_int($a, $b));
    }

    public function testDivModFromUuid2(): void
    {
        $a = u::from_hex('1ee9537cf605180', 8);
        $b = 10000000;

        list($d, $m) = (u::div_mod_int($a, $b));

        self::assertEquals('13921270543', u::to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, u::mod_int($a, $b));
    }

    public function testDivModFromUuid2SameCaseButFor64Bit(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $a = u::from_hex('47f8a5ed517db9349160000', 16);
        $b = 100000000000000000;

        list($d, $m) = (u::div_mod_int($a, $b));

        self::assertEquals('13921270543', u::to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, u::mod_int($a, $b));
    }

    public function testDivModBigButBFits(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $a = u::from_hex('73276fe21bfc5b874f0000', 16);
        $b = 10000000000000000;

        list($d, $m) = (u::div_mod_int($a, $b));

        self::assertEquals('13921270543', u::to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, u::mod_int($a, $b));
    }

    public function testModNoZero(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Modulo by zero');

        u::mod_int(u::from_int(123456, 8), 0);
    }

    public function testModNoNeg(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use mod($a, from_int($b)) for unsigned logic');

        u::mod_int(u::from_int(123456, 8), -2);
    }
}
