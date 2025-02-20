<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\add;
use function Arokettu\Unsigned\div;
use function Arokettu\Unsigned\div_mod;
use function Arokettu\Unsigned\from_hex;
use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\Internal\_raw_mul32;
use function Arokettu\Unsigned\mod;
use function Arokettu\Unsigned\mul;
use function Arokettu\Unsigned\neg;
use function Arokettu\Unsigned\sub;
use function Arokettu\Unsigned\to_hex;
use function Arokettu\Unsigned\to_int;

class ArithmeticTest extends TestCase
{
    public function testSum()
    {
        // normal
        self::assertEquals(
            123456 + 654321,
            to_int(add(from_int(123456, \PHP_INT_SIZE), from_int(654321, \PHP_INT_SIZE)))
        );
        //overflow
        self::assertEquals(
            (123 + 234) & 255,
            to_int(add(from_int(123, 1), from_int(234, 1)))
        );
    }

    public function testSumDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 1 and 2 bytes given');

        add("\0", "\0\0");
    }

    public function testSub()
    {
        // normal
        self::assertEquals(
            654321 - 123456,
            to_int(sub(from_int(654321, \PHP_INT_SIZE), from_int(123456, \PHP_INT_SIZE)))
        );
        // overflow
        self::assertEquals(
            (123456 - 654321) & \PHP_INT_MAX >> 7,
            to_int(sub(from_int(123456, \PHP_INT_SIZE - 1), from_int(654321, \PHP_INT_SIZE - 1)))
        );
    }

    public function testNeg()
    {
        // something that converts to int
        self::assertEquals(
            123,
            to_int(neg(from_int(-123, \PHP_INT_SIZE)))
        );
        // 0
        self::assertEquals(
            0,
            to_int(neg(from_int(0, \PHP_INT_SIZE)))
        );
        // small size
        self::assertEquals(
            255,
            to_int(neg(from_int(1, 1)))
        );
    }

    public function testSubDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 1 and 2 bytes given');

        sub("\0", "\0\0");
    }

    public function testMul()
    {
        // normal
        self::assertEquals(
            11111 * 11111,
            to_int(mul(from_int(11111, \PHP_INT_SIZE), from_int(11111, \PHP_INT_SIZE)))
        );
        //overflow
        self::assertEquals(
            (11111 * 11111) & 65535,
            to_int(mul(from_int(11111, 2), from_int(11111, 2)))
        );
        // 0
        self::assertEquals(
            0,
            to_int(mul(from_int(11111, 2), from_int(0, 2)))
        );
        // 1
        self::assertEquals(
            11111,
            to_int(mul(from_int(11111, 2), from_int(1, 2)))
        );
        // -1
        self::assertEquals(
            from_int(-11111, 2),
            mul(from_int(11111, 2), from_int(-1, 2))
        );
        // $a does not fit into int
        self::assertEquals(
            '01234566666666666666666666654321',
            to_hex(mul(from_hex('11111111111111111111111111', 16), from_hex('111111', 16)))
        );
        // both don't fit
        self::assertEquals(
            '01234566666666666666666666654321',
            to_hex(mul(from_hex('11111111111111111111111111', 16), from_hex('111111', 16)))
        );
        self::assertEquals(
            '56789aba987654320fedcba987654321',
            to_hex(mul(from_hex('11111111111111111111111111', 16), from_hex('11111111111111111111111111', 16)))
        );
        // infinite recursion detected
        self::assertEquals(
            1,
            to_int(mul(from_int(\PHP_INT_MAX, \PHP_INT_SIZE), from_int(\PHP_INT_MAX, \PHP_INT_SIZE)))
        );
    }

    public function testMul32()
    {
        // normal
        self::assertEquals(
            11111 * 11111,
            to_int(_raw_mul32(from_int(11111, \PHP_INT_SIZE), from_int(11111, \PHP_INT_SIZE), \PHP_INT_SIZE))
        );
        //overflow
        self::assertEquals(
            (11111 * 11111) & 65535,
            to_int(_raw_mul32(from_int(11111, 2), from_int(11111, 2), 2))
        );
        // 0
        self::assertEquals(
            0,
            to_int(_raw_mul32(from_int(11111, 2), from_int(0, 2), 2))
        );
        // 1
        self::assertEquals(
            11111,
            to_int(_raw_mul32(from_int(11111, 2), from_int(1, 2), 2))
        );
        // -1
        self::assertEquals(
            from_int(-11111, 2),
            _raw_mul32(from_int(11111, 2), from_int(-1, 2), 2)
        );
        // $a does not fit into int
        self::assertEquals(
            '01234566666666666666666666654321',
            to_hex(_raw_mul32(from_hex('11111111111111111111111111', 16), from_hex('111111', 16), 16))
        );
        // both don't fit
        self::assertEquals(
            '01234566666666666666666666654321',
            to_hex(_raw_mul32(from_hex('11111111111111111111111111', 16), from_hex('111111', 16), 16))
        );
        self::assertEquals(
            '56789aba987654320fedcba987654321',
            to_hex(_raw_mul32(
                from_hex('11111111111111111111111111', 16),
                from_hex('11111111111111111111111111', 16),
                16
            ))
        );
        // infinite recursion detected
        self::assertEquals(
            1,
            to_int(_raw_mul32(
                from_int(\PHP_INT_MAX, \PHP_INT_SIZE),
                from_int(\PHP_INT_MAX, \PHP_INT_SIZE),
                \PHP_INT_SIZE
            ))
        );
    }

    public function testMulDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 1 and 2 bytes given');

        mul("\0", "\0\0");
    }

    public function testDiv()
    {
        self::assertEquals(\intdiv(123456, 1000), to_int(div(from_int(123456, 8), from_int(1000, 8))));
        self::assertEquals(\intdiv(123456, 1), to_int(div(from_int(123456, 8), from_int(1, 8))));
        self::assertEquals(\intdiv(123456, 1024), to_int(div(from_int(123456, 8), from_int(1024, 8))));
        self::assertEquals(\intdiv(123456, 654321), to_int(div(from_int(123456, 8), from_int(654321, 8))));
        self::assertEquals(\intdiv(123456, 123456), to_int(div(from_int(123456, 8), from_int(123456, 8))));
        // negative is accepted
         self::assertEquals(0, to_int(div(from_int(123456, 8), from_int(-1000, 8))));
        // 4 bytes by 1 byte
        self::assertEquals(
            \intdiv(0x4f423f23, 0x45),
            to_int(div(from_int(0x4f423f23, 8), from_int(0x45, 8)))
        );
        // 4 bytes by 4 bytes
        self::assertEquals(
            \intdiv(0x4f423f23, 0x1257ac45),
            to_int(div(from_int(0x4f423f23, 8), from_int(0x1257ac45, 8)))
        );
        // 32 bytes by 9 bytes
        self::assertEquals(
            '0000000000000000032b13cb42f11b51a9655f6e1d1cf2354907e0cbcc7ae6cd',
            to_hex(div(
                from_hex('64fc4b486b2c1cbd14171f5e5e0b2eaf71b572afbaedd62caf2570c5de320073', 32),
                from_hex('00000000000000000000000000000000000000000000001fdfbfb8cbd41dd1ed', 32)
            ))
        );
        // 32 bytes by 32 bytes
        self::assertEquals(
            '0000000000000000000000000000000000000000000000000000000000000002',
            to_hex(div(
                from_hex('eca097608c3c403463d2d437fa67362d4d49bdc0e322df559960ef4dfd3dac50', 32),
                from_hex('64fc4b486b2c1cbd14171f5e5e0b2eaf71b572afbaedd62caf2570c5de320073', 32)
            ))
        );

        // edge case found
        self::assertEquals(
            14,
            to_int(div(
                from_hex('ffffffffffffffff', 8), // 2**64-1
                from_hex('112210f47de98116', 8)  // 1234567890123456790
            ))
        );

        // big pow2
        self::assertEquals(
            [
                from_hex('112233', 16),
                from_hex('445566778899aabbccddeeff00', 16),
            ],
            div_mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00000100000000000000000000000000', 16)
            )
        );
        self::assertEquals(
            [
                from_hex('89119', 16),
                from_hex('1445566778899aabbccddeeff00', 16),
            ],
            div_mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00000200000000000000000000000000', 16)
            )
        );
        self::assertEquals(
            [
                from_hex('4488c', 16),
                from_hex('3445566778899aabbccddeeff00', 16),
            ],
            div_mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00000400000000000000000000000000', 16)
            )
        );
        self::assertEquals(
            [
                from_hex('22446', 16),
                from_hex('3445566778899aabbccddeeff00', 16),
            ],
            div_mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00000800000000000000000000000000', 16)
            )
        );

        // mod 0
        self::assertEquals(
            [from_int(0x100, 16), from_int(0, 16)],
            div_mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00112233445566778899aabbccddeeff', 16)
            )
        );
    }

    public function testDivDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 1 and 2 bytes given');

        div("\0", "\0\0");
    }

    public function testDivNoZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Division by zero');

        div(from_int(123456, 8), from_int(0, 8));
    }

    public function testMod()
    {
        self::assertEquals(123456 % 1000, to_int(mod(from_int(123456, 8), from_int(1000, 8))));
        self::assertEquals(123456 % 1, to_int(mod(from_int(123456, 8), from_int(1, 8))));
        self::assertEquals(123456 % 1024, to_int(mod(from_int(123456, 8), from_int(1024, 8))));
        self::assertEquals(123456 % 654321, to_int(mod(from_int(123456, 8), from_int(654321, 8))));
        self::assertEquals(123456 % 123456, to_int(mod(from_int(123456, 8), from_int(123456, 8))));
        // negative is accepted
        self::assertEquals(123456, to_int(mod(from_int(123456, 8), from_int(-1000, 8))));
        // 4 bytes by 1 byte
        self::assertEquals(
            0x4f423f23 % 0x45,
            to_int(mod(from_int(0x4f423f23, 8), from_int(0x45, 8)))
        );
        // 4 bytes by 4 bytes
        self::assertEquals(
            0x4f423f23 % 0x1257ac45,
            to_int(mod(from_int(0x4f423f23, 8), from_int(0x1257ac45, 8)))
        );

        // verify length
        self::assertEquals(8, \strlen(mod(from_int(0x4f423f23, 8), from_int(0x45, 8))));
        self::assertEquals(8, \strlen(mod(from_int(0x4f423f23, 8), from_int(0x1257ac45, 8))));

        // 32 bytes by 9 bytes
        self::assertEquals(
            '000000000000000000000000000000000000000000000006269ca48c50c3f7aa',
            to_hex(mod(
                from_hex('64fc4b486b2c1cbd14171f5e5e0b2eaf71b572afbaedd62caf2570c5de320073', 32),
                from_hex('00000000000000000000000000000000000000000000001fdfbfb8cbd41dd1ed', 32)
            ))
        );
        // 32 bytes by 32 bytes
        self::assertEquals(
            '22a800cfb5e406ba3ba4957b3e50d8ce69ded8616d4732fc3b160dc240d9ab6a',
            to_hex(mod(
                from_hex('eca097608c3c403463d2d437fa67362d4d49bdc0e322df559960ef4dfd3dac50', 32),
                from_hex('64fc4b486b2c1cbd14171f5e5e0b2eaf71b572afbaedd62caf2570c5de320073', 32)
            ))
        );

        // edge case found
        self::assertEquals(
            '102312a11d3af0cb',
            to_hex(mod(
                from_hex('ffffffffffffffff', 8), // 2**64-1
                from_hex('112210f47de98116', 8)  // 1234567890123456790
            ))
        );

        // big pow2
        self::assertEquals(
            from_hex('445566778899aabbccddeeff00', 16),
            mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00000100000000000000000000000000', 16)
            )
        );

        // mod 0
        self::assertEquals(
            from_int(0, 16),
            mod(
                from_hex('112233445566778899aabbccddeeff00', 16),
                from_hex('00112233445566778899aabbccddeeff', 16)
            )
        );
    }

    public function testModNoZero()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Modulo by zero');

        mod(from_int(123456, 8), from_int(0, 8));
    }

    public function testModDifferentSizes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Arguments must be the same size, 1 and 2 bytes given');

        mod("\0", "\0\0");
    }

    public function testDivMod()
    {
//        self::assertEquals(123456 % 1000, to_int(mod(from_int(123456, 8), from_int(1000, 8)))));
        self::assertEquals(123456 % 1, to_int(div_mod(from_int(123456, 8), from_int(1, 8))[1]));
        self::assertEquals(123456 % 1024, to_int(div_mod(from_int(123456, 8), from_int(1024, 8))[1]));
        self::assertEquals(123456 % 654321, to_int(div_mod(from_int(123456, 8), from_int(654321, 8))[1]));
        self::assertEquals(123456 % 123456, to_int(div_mod(from_int(123456, 8), from_int(123456, 8))[1]));
        // negative is accepted
        // self::assertEquals(...?, to_int(mod(from_int(123456, 8), from_int(-1000, 8)))));
    }
}
