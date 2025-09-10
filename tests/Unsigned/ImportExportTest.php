<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use Arokettu\Random\Unsigned\Unsigned as u;
use PHPUnit\Framework\TestCase;

final class ImportExportTest extends TestCase
{
    public function testFromInt(): void
    {
        // target below PHP_INT_SIZE

        // positive
        self::assertEquals("\x23\x01", u::from_int(0x123, 2));
        // zero
        self::assertEquals("\0\0", u::from_int(0, 2));
        // negative
        self::assertEquals("\xf0\xff", u::from_int(-0x10, 2));

        // target PHP_INT_SIZE

        // positive
        self::assertEquals(\str_pad("\x23\x01", \PHP_INT_SIZE, "\0"), u::from_int(0x123, \PHP_INT_SIZE));
        // zero
        self::assertEquals(\str_repeat("\0", \PHP_INT_SIZE), u::from_int(0, \PHP_INT_SIZE));
        // negative
        self::assertEquals(\str_pad("\xf0\xff", \PHP_INT_SIZE, "\xff"), u::from_int(-0x10, \PHP_INT_SIZE));

        // target above PHP_INT_SIZE

        if (\PHP_INT_SIZE > 8) {
            throw new \LogicException('The future arrived! Update tests!');
        }

        // positive
        self::assertEquals("\x78\x56\x34\x12\0\0\0\0\0\0\0\0\0\0\0\0", u::from_int(0x12345678, 16));
        // zero
        self::assertEquals("\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0", u::from_int(0, 16));
        // negative
        self::assertEquals("\0\0\0\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff", u::from_int(-0x1000000, 16));
    }

    public function testFromIntTruncate(): void
    {
        self::assertEquals(1193046 & 65535, u::to_int(u::from_int(1193046, 2)));
        self::assertEquals(-6636321 & 65535, u::to_int(u::from_int(-6636321, 2)));
    }

    public function testToInt(): void
    {
        self::assertEquals(0x123, u::to_int("\x23\x01"));
        self::assertEquals(\PHP_INT_MAX, u::to_int(u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE)));
        // negative of lower size than PHP_INT
        self::assertEquals(-1 & \PHP_INT_MAX >> 7, u::to_int(u::from_int(-1, \PHP_INT_SIZE - 1)));
    }

    public function testToIntTooBig(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('The value is larger than PHP integer');

        u::to_int(\str_repeat("\xff", \PHP_INT_SIZE + 1));
    }

    public function testToIntTooBigNeg(): void
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('The value is larger than PHP integer');

        // negative of equal or greater size than PHP_INT
        u::to_int(u::from_int(-1, \PHP_INT_SIZE));
    }

    public function testToSigned(): void
    {
        self::assertEquals(0, u::to_signed_int(u::from_int(0, \PHP_INT_SIZE)));
        self::assertEquals(1, u::to_signed_int(u::from_int(1, \PHP_INT_SIZE)));
        self::assertEquals(-1, u::to_signed_int(u::from_int(-1, \PHP_INT_SIZE)));
        self::assertEquals(123, u::to_signed_int(u::from_int(123, \PHP_INT_SIZE)));
        self::assertEquals(-123, u::to_signed_int(u::from_int(-123, \PHP_INT_SIZE)));
        self::assertEquals(\PHP_INT_MAX, u::to_signed_int(u::from_int(\PHP_INT_MAX, \PHP_INT_SIZE)));
        self::assertEquals(\PHP_INT_MIN, u::to_signed_int(u::from_int(\PHP_INT_MIN, \PHP_INT_SIZE)));
    }

    public function testFromHex(): void
    {
        // exact
        self::assertEquals("\x23\x01", u::from_hex('0123', 2));
        // truncate
        self::assertEquals("\x23", u::from_hex('0123', 1));
        // pad
        self::assertEquals("\x23\x01\x00", u::from_hex('0123', 3));
        // odd number of digits is acceptable too!
        self::assertEquals("\x23\x01\x00\x00", u::from_hex('123', 4));
    }

    public function testToHex(): void
    {
        self::assertEquals('000123', u::to_hex(u::from_int(0x123, 3)));
    }

    public function testFromDec(): void
    {
        self::assertEquals(123456789, u::to_int(u::from_dec('123456789', 16)));
        self::assertEquals(123456789 & 255 /*21*/, u::to_int(u::from_dec('123456789', 1)));
        self::assertEquals(0, u::to_int(u::from_dec('0', 16)));
    }

    public function testFromDecOnlyDigits(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$value contains invalid digits');

        u::from_dec('abc', 16);
    }

    public function testToDec(): void
    {
        self::assertEquals('123456789', u::to_dec(u::from_int(123456789, 16)));
        self::assertEquals('21', u::to_dec(u::from_int(123456789, 1)));
        self::assertEquals('0', u::to_dec(u::from_int(0, 16)));
        self::assertEquals('340282366920938463463374607431768211455', u::to_dec(u::from_int(-1, 16)));
    }

    public function testToBase(): void
    {
        $num = u::from_int(123, 3);

        self::assertEquals('1111011', u::to_base($num, 2));
        self::assertEquals('173', u::to_base($num, 8));
        self::assertEquals('123', u::to_base($num, 10));
        self::assertEquals('a3', u::to_base($num, 12));
        self::assertEquals('96', u::to_base($num, 13));
        self::assertEquals('7b', u::to_base($num, 16));
        self::assertEquals('53', u::to_base($num, 24));
        self::assertEquals('3f', u::to_base($num, 36));
    }

    public function testToBaseMax(): void
    {
        $num = u::from_int(-1, 3);

        self::assertEquals('111111111111111111111111', u::to_base($num, 2));
        self::assertEquals('77777777', u::to_base($num, 8));
        self::assertEquals('16777215', u::to_base($num, 10));
        self::assertEquals('5751053', u::to_base($num, 12));
        self::assertEquals('3625560', u::to_base($num, 13));
        self::assertEquals('ffffff', u::to_base($num, 16));
        self::assertEquals('22df2f', u::to_base($num, 24));
        self::assertEquals('9zldr', u::to_base($num, 36));
    }

    public function testToBase16(): void
    {
        $num = u::from_int(31415926, 8);

        // while from_base(..., 16) and from_hex(...) are equivalent,
        // to_base(..., 16) and to_hex(...) are not
        self::assertEquals('1df5e76', u::to_base($num, 16));
        self::assertEquals('0000000001df5e76', u::to_hex($num));
    }

    public function testToBaseInvalid(): void
    {
        $num = u::from_int(-1, 3);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$base must be between 2 and 36');

        u::to_base($num, 37);
    }

    public function testFromBase(): void
    {
        $num = \bin2hex(u::from_int(-1, 3));

        self::assertEquals($num, \bin2hex(u::from_base('111111111111111111111111', 2, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('77777777', 8, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('16777215', 10, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('5751053', 12, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('3625560', 13, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('ffffff', 16, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('22df2f', 24, 3)));
        self::assertEquals($num, \bin2hex(u::from_base('9zldr', 36, 3)));
    }

    public function testFromBaseInvalidBase(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$base must be between 2 and 36');

        u::from_base('111121111', 1, 3);
    }

    public function testFromBaseInvalidDigits2(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$value contains invalid digits');

        u::from_base('111121111', 2, 3);
    }

    public function testFromBaseInvalidDigits10(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$value contains invalid digits');

        u::from_base('1111a1111', 2, 3);
    }

    public function testFromBaseInvalidDigits36(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('$value contains invalid digits');

        u::from_base('1111?111', 2, 3);
    }

    public function testFitsIntoInt(): void
    {
        self::assertTrue(u::fits_into_int("\0"));

        self::assertTrue(u::fits_into_int("\1\1\1\1")); // fits in 32
        self::assertEquals(\PHP_INT_SIZE > 4, u::fits_into_int("\1\1\1\1\1")); // fits in 64 , doesn't in 32
        self::assertEquals(\PHP_INT_SIZE > 4, u::fits_into_int("\1\1\1\xff")); // fits in 64 , doesn't in 32

        if (\PHP_INT_SIZE > 8) {
            throw new \LogicException('The future arrived! Update tests!');
        }

        self::assertEquals(\PHP_INT_SIZE > 4, u::fits_into_int("\1\1\1\1\1\1\1\1")); // fits in 64 , doesn't in 32
        self::assertFalse(u::fits_into_int("\1\1\1\1\1\1\1\1\1"));
        self::assertFalse(u::fits_into_int("\1\1\1\1\1\1\1\xff"));
    }
}
