<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\add_int;
use function Arokettu\Unsigned\div_int;
use function Arokettu\Unsigned\div_mod_int;
use function Arokettu\Unsigned\from_hex;
use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\mod_int;
use function Arokettu\Unsigned\mul_int;
use function Arokettu\Unsigned\sub_int;
use function Arokettu\Unsigned\sub_int_rev;
use function Arokettu\Unsigned\to_dec;
use function Arokettu\Unsigned\to_hex;
use function Arokettu\Unsigned\to_int;

class ArithmeticIntTest extends TestCase
{
    public function testSum()
    {
        // normal
        self::assertEquals(
            123456 + 654321,
            to_int(add_int(from_int(123456, \PHP_INT_SIZE), 654321))
        );
        //overflow
        self::assertEquals(
            (123 + 234) & 255,
            to_int(add_int(from_int(123, 1), 234))
        );
        // zero
        self::assertEquals(
            123456,
            to_int(add_int(from_int(123456, \PHP_INT_SIZE), 0))
        );
        // negative
        self::assertEquals(
            123456 - 456,
            to_int(add_int(from_int(123456, \PHP_INT_SIZE), -456))
        );
        // int overflow
        self::assertEquals(
            from_int(-2, \PHP_INT_SIZE),
            add_int(from_int(\PHP_INT_MAX, \PHP_INT_SIZE), \PHP_INT_MAX)
        );
    }

    public function testAddOverlfow64()
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxAdd = 9223372036854775552;
        $overflow = 9223372036854775553;

        self::assertEquals(
            '7fffffffffffffff',
            to_hex(add_int(from_hex("ff", 8), $maxAdd))
        );
        self::assertEquals(
            '800000000000feff',
            to_hex(add_int(from_hex("ffff", 8), $maxAdd))
        );
        self::assertEquals(
            '8000000000000000',
            to_hex(add_int(from_hex("ff", 8), $overflow))
        );
        self::assertEquals(
            '800000000000ff00',
            to_hex(add_int(from_hex("ffff", 8), $overflow))
        );
    }

    public function testAddOverlfow32()
    {
        $maxAdd = 2147483392;
        $overflow = 2147483393;

        self::assertEquals(
            '7fffffff',
            to_hex(add_int(from_hex("ff", 4), $maxAdd))
        );
        self::assertEquals(
            '8000feff',
            to_hex(add_int(from_hex("ffff", 4), $maxAdd))
        );
        self::assertEquals(
            '80000000',
            to_hex(add_int(from_hex("ff", 4), $overflow))
        );
        self::assertEquals(
            '8000ff00',
            to_hex(add_int(from_hex("ffff", 4), $overflow))
        );
    }

    public function testSub()
    {
        // normal
        self::assertEquals(
            654321 - 123456,
            to_int(sub_int(from_int(654321, \PHP_INT_SIZE), 123456))
        );
        // overflow
        self::assertEquals(
            (123456 - 654321) & \PHP_INT_MAX >> 7,
            to_int(sub_int(from_int(123456, \PHP_INT_SIZE - 1), 654321))
        );
        // special
        self::assertEquals(
            123456, // zeros if sign is truncated
            to_int(sub_int(from_int(123456, \PHP_INT_SIZE - 1), \PHP_INT_MIN))
        );
    }

    public function testSubRev()
    {
        // normal
        self::assertEquals(
            654321 - 123456,
            to_int(sub_int_rev(654321, from_int(123456, \PHP_INT_SIZE)))
        );
        // overflow
        self::assertEquals(
            (123456 - 654321) & \PHP_INT_MAX >> 7,
            to_int(sub_int_rev(123456, from_int(654321, \PHP_INT_SIZE - 1)))
        );
        // not special but check anyway
        self::assertEquals(
            \PHP_INT_MAX - 123455, // overflow
            to_int(sub_int_rev(\PHP_INT_MIN, from_int(123456, \PHP_INT_SIZE)))
        );
    }

    public function testMul()
    {
        // normal
        self::assertEquals(
            11111 * 11111,
            to_int(mul_int(from_int(11111, \PHP_INT_SIZE), 11111))
        );
        // overflow
        self::assertEquals(
            (11111 * 11111) & 65535,
            to_int(mul_int(from_int(11111, 2), 11111))
        );
        // 0
        self::assertEquals(
            0,
            to_int(mul_int(from_int(11111, 2), 0))
        );
        // 1
        self::assertEquals(
            11111,
            to_int(mul_int(from_int(11111, 2), 1))
        );
        // -1
        self::assertEquals(
            from_int(-11111, 2),
            mul_int(from_int(11111, 2), -1)
        );
        // negative
        self::assertEquals(
            from_int(-11111 * 11111, 2),
            mul_int(from_int(11111, 2), -11111)
        );
        // special case
        self::assertEquals(
            0, // multiplying by even number will carry sign beyond overflow
            to_int(mul_int(from_int(11110, \PHP_INT_SIZE), \PHP_INT_MIN))
        );
        // int overflow
        self::assertEquals(
            65413,
            to_int(mul_int(from_int(123, 2), \PHP_INT_MAX))
        );
    }

    public function testMulOverflow64()
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxMul = 0x80000000000000;
        $overflow = 0x80000000000001;

        self::assertEquals(
            '007fffffffffffffff80000000000000',
            to_hex(mul_int(from_hex("ffffffffffffffff", 16), $maxMul))
        );
        self::assertEquals(
            '0080000000000000ff7fffffffffffff',
            to_hex(mul_int(from_hex("ffffffffffffffff", 16), $overflow))
        );
    }

    public function testMulOverflow32()
    {
        $maxMul = 0x800000;
        $overflow = 0x800001;

        self::assertEquals(
            '007fffffff800000',
            to_hex(mul_int(from_hex("ffffffff", 8), $maxMul))
        );
        self::assertEquals(
            '00800000ff7fffff',
            to_hex(mul_int(from_hex("ffffffff", 8), $overflow))
        );
    }

    public function testDiv()
    {
        self::assertEquals(\intdiv(123456, 1000), to_int(div_int(from_int(123456, 8), 1000)));
        self::assertEquals(\intdiv(123456, 1), to_int(div_int(from_int(123456, 8), 1)));
        self::assertEquals(\intdiv(123456, 1024), to_int(div_int(from_int(123456, 8), 1024)));
        self::assertEquals(\intdiv(123456, 654321), to_int(div_int(from_int(123456, 8), 654321)));
        self::assertEquals(\intdiv(123456, 123456), to_int(div_int(from_int(123456, 8), 123456)));
    }

    public function testDivNoZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Division by zero');

        div_int(from_int(123456, 8), 0);
    }

    public function testDivNoNeg()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use div($a, from_int($b)) for unsigned logic');

        div_int(from_int(123456, 8), -2);
    }

    public function testMod()
    {
        self::assertEquals(123456 % 1000, mod_int(from_int(123456, 8), 1000));
        self::assertEquals(123456 % 1, mod_int(from_int(123456, 8), 1));
        self::assertEquals(123456 % 1024, mod_int(from_int(123456, 8), 1024));
        self::assertEquals(123456 % 654321, mod_int(from_int(123456, 8), 654321));
        self::assertEquals(123456 % 123456, mod_int(from_int(123456, 8), 123456));

        // big pow2
        self::assertEquals(
            0xeeff00,
            mod_int(
                from_hex('112233445566778899aabbccddeeff00', 16),
                2 ** 24
            )
        );
    }

    public function testModOverflow64()
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $maxDiv = 0x0ffffffffffffff;
        // skip pow2
        $overflow = 0x100000000000001;

        self::assertEquals(
            255,
            mod_int(
                from_hex('ffffffffffffffff', 8),
                $maxDiv
            )
        );
        self::assertEquals(
            72057594037927680,
            mod_int(
                from_hex('ffffffffffffffff', 8),
                $overflow
            )
        );
    }

    public function testDivMod()
    {
        self::assertEquals(
            div_mod_int(from_int(123456, 8), 1000)[1],
            mod_int(from_int(123456, 8), 1000)
        );
        self::assertEquals(
            div_mod_int(from_int(123456, 8), 1)[1],
            mod_int(from_int(123456, 8), 1)
        );
        self::assertEquals(
            div_mod_int(from_int(123456, 8), 1024)[1],
            mod_int(from_int(123456, 8), 1024)
        );
        self::assertEquals(
            div_mod_int(from_int(123456, 8), 654321)[1],
            mod_int(from_int(123456, 8), 654321)
        );
        self::assertEquals(
            div_mod_int(from_int(123456, 8), 123456)[1],
            mod_int(from_int(123456, 8), 123456)
        );
        // big pow2
        self::assertEquals(
            [\strrev(\hex2bin('000000112233445566778899aabbccdd')), 0xeeff00],
            div_mod_int(
                from_hex('112233445566778899aabbccddeeff00', 16),
                2 ** 24
            )
        );
    }

    public function testDivModNoNeg()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use div_mod($a, from_int($b)) for unsigned logic');

        div_mod_int(from_int(123456, 8), -2);
    }

    public function testDivModFromUuid()
    {
        $a = from_hex('200000000000011', 8);
        $b = 10000000;

        list($d, $m) = div_mod_int($a, $b);

        self::assertEquals('14411518807', to_dec($d));
        self::assertEquals(5855889, $m);

        // also check simple mod
        self::assertEquals(5855889, mod_int($a, $b));
    }

    public function testDivModFromUuid2()
    {
        $a = from_hex('1ee9537cf605180', 8);
        $b = 10000000;

        list($d, $m) = div_mod_int($a, $b);

        self::assertEquals('13921270543', to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, mod_int($a, $b));
    }

    public function testDivModFromUuid2SameCaseButFor64Bit()
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $a = from_hex('47f8a5ed517db9349160000', 16);
        $b = 100000000000000000;

        list($d, $m) = div_mod_int($a, $b);

        self::assertEquals('13921270543', to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, mod_int($a, $b));
    }

    public function testDivModBigButBFits()
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped();
        }

        $a = from_hex('73276fe21bfc5b874f0000', 16);
        $b = 10000000000000000;

        list($d, $m) = div_mod_int($a, $b);

        self::assertEquals('13921270543', to_dec($d));
        self::assertEquals(0, $m);

        // also check simple mod
        self::assertEquals(0, mod_int($a, $b));
    }

    public function testModNoZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Modulo by zero');

        mod_int(from_int(123456, 8), 0);
    }

    public function testModNoNeg()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$b must be greater than zero. Use mod($a, from_int($b)) for unsigned logic');

        mod_int(from_int(123456, 8), -2);
    }
}
