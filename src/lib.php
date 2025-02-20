<?php

/**
 * @noinspection DuplicatedCode
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */

declare(strict_types=1);

namespace Arokettu\Unsigned
{
    use Arokettu\Unsigned as u;
    use Arokettu\Unsigned\Internal as i;

    function from_int(int $value, int $sizeof): string
    {
        $hex = \dechex($value);
        $strlen = \strlen($hex);
        $hexsize = $sizeof * 2;

        switch ($strlen <=> $hexsize) {
            case -1:
                // pad according to sign
                $hex = \str_pad($hex, $hexsize, $value >= 0 ? '0' : 'f', \STR_PAD_LEFT);
                break;

            case 1:
                // truncate
                $hex = \substr($hex, -$hexsize);
                break;
        }

        return \strrev(\hex2bin($hex));
    }

    function to_int(string $value): int
    {
        if (!u\fits_into_int($value)) {
            throw new \RangeException('The value is larger than PHP integer');
        }

        return \hexdec(\bin2hex(\strrev($value)));
    }

    function to_signed_int(string $value): int
    {
        $sizeof = \strlen($value);
        if (u\is_bit_set($value, $sizeof * 8 - 1)) {
            $value = ~$value;
            return -u\to_int($value) - 1;
        }

        return u\to_int($value);
    }

    function fits_into_int(string $value): bool
    {
        $value = \rtrim($value, "\0");
        $sizeof = \strlen($value);

        $notFits =
            $sizeof > \PHP_INT_SIZE ||
            $sizeof === \PHP_INT_SIZE && \ord($value[\PHP_INT_SIZE - 1]) > 127;

        return !$notFits;
    }

    function from_hex(string $value, int $sizeof): string
    {
        $strlen = \strlen($value);
        $hexsize = $sizeof * 2;

        switch ($strlen <=> $hexsize) {
            case -1:
                // pad with zeros
                $value = \str_pad($value, $hexsize, '0', \STR_PAD_LEFT);
                break;

            case 1:
                // truncate
                $value = \substr($value, -$hexsize);
                break;
        }

        return \strrev(\hex2bin($value));
    }

    function to_hex(string $value): string
    {
        return \bin2hex(\strrev($value));
    }

    function from_base(string $value, int $base, int $sizeof): string
    {
        if ($base < 2 || $base > 36) {
            throw new \DomainException('$base must be between 2 and 36');
        }

        $chars = \substr(i\ALPHABET, 0, $base);

        if (!\preg_match("/^[{$chars}]+$/i", $value)) {
            throw new \DomainException('$value contains invalid digits');
        }

        if ($base === 16) {
            return u\from_hex($value, $sizeof);
        }

        $value = \strtolower($value);
        $result = \str_repeat("\0", $sizeof);

        for ($i = 0; $i < \strlen($value); $i++) {
            $result = u\add_int(
                u\mul_int($result, $base),
                i\DIGIT_VALUE[$value[$i]]
            );
        }

        return $result;
    }

    function to_base(string $value, int $base): string
    {
        if ($base < 2 || $base > 36) {
            throw new \DomainException('$base must be between 2 and 36');
        }

        $result = '';

        $zero = \str_repeat("\0", \strlen($value));

        do {
            list($value, $mod) = u\div_mod_int($value, $base);
            $result .= i\ALPHABET[$mod];
        } while ($value !== $zero);

        return \strrev($result);
    }

    function from_dec(string $value, int $sizeof): string
    {
        return u\from_base($value, 10, $sizeof);
    }

    function to_dec(string $value): string
    {
        return u\to_base($value, 10);
    }

    /**
     * $value << $shift
     */
    function shift_left(string $value, int $shift): string
    {
        $sizeof = \strlen($value);

        if ($shift < 0) {
            throw new \DomainException('$shift must be non negative');
        }
        if ($shift >= $sizeof * 8) {
            return \str_repeat("\0", $sizeof);
        }
        if ($shift >= 8) {
            $easyShift = $shift >> 3; // div 8
            $shift = $shift & 7; // mod 8
            $value = \str_repeat("\0", $easyShift) . \substr($value, 0, $sizeof - $easyShift);
        }
        if ($shift === 0) {
            return $value;
        }

        $carry = 0;
        for ($i = 0; $i < $sizeof; $i++) {
            $newChr = \ord($value[$i]) << $shift | $carry;
            $value[$i] = \chr($newChr);
            $carry = $newChr >> 8;
        }

        return $value;
    }

    /**
     * $value >> $shift
     */
    function shift_right(string $value, int $shift): string
    {
        $sizeof = \strlen($value);

        if ($shift < 0) {
            throw new \DomainException('$shift must be non negative');
        }
        if ($shift >= $sizeof * 8) {
            return \str_repeat("\0", $sizeof);
        }
        if ($shift >= 8) {
            $easyShift = $shift >> 3; // div 8
            $shift = $shift & 7; // mod 8
            $value = \substr($value, $easyShift) . \str_repeat("\0", $easyShift);
        }
        if ($shift === 0) {
            return $value;
        }

        // shift left 8 - $shift bits, then remove the least significant byte
        $carry = 0;
        $shift = 8 - $shift;
        for ($i = 0; $i < $sizeof; $i++) {
            $newChr = \ord($value[$i]) << $shift | $carry;
            $value[$i] = \chr($newChr);
            $carry = $newChr >> 8;
        }

        $value[$i] = \chr($carry);

        return \substr($value, 1);
    }

    /**
     * a + b
     */
    function add(string $a, string $b): string
    {
        $sizeof = \strlen($a);
        $sizeofb = \strlen($b);
        if ($sizeof !== $sizeofb) {
            throw new \DomainException("Arguments must be the same size, $sizeof and $sizeofb bytes given");
        }

        $carry = 0;
        for ($i = 0; $i < $sizeof; ++$i) {
            $newChr = \ord($a[$i]) + \ord($b[$i]) + $carry;
            $a[$i] = \chr($newChr);
            $carry = $newChr >> 8;
        }

        return $a;
    }

    /**
     * a + int(b)
     */
    function add_int(string $a, int $b): string
    {
        $sizeof = \strlen($a);

        if ($b === 0) {
            return $a;
        }
        if ($b > i\MAX_ADD) {
            return u\add($a, u\from_int($b, $sizeof));
        }

        $r = $a;
        $carry = $b;
        for ($i = 0; $i < $sizeof; ++$i) {
            if ($carry === 0) {
                break;
            }
            $newChr = \ord($r[$i]) + $carry;
            $r[$i] = \chr($newChr);
            $carry = $newChr >> 8;
        }

        return $r;
    }

    /**
     * a - b
     */
    function sub(string $a, string $b): string
    {
        return u\add($a, u\neg($b));
    }

    /**
     * a - int(b)
     */
    function sub_int(string $a, int $b): string
    {
        // handle overflow on negative
        if ($b === \PHP_INT_MIN) {
            return u\sub($a, u\from_int($b, \strlen($a)));
        }
        return u\add_int($a, -$b);
    }

    /**
     * int(a) - b
     */
    function sub_int_rev(int $a, string $b): string
    {
        return u\add_int(u\neg($b), $a);
    }

    /**
     * -a
     */
    function neg(string $a): string
    {
        return u\add_int(~$a, 1);
    }

    /**
     * a * b
     */
    function mul(string $a, string $b): string
    {
        return i\_raw_mul($a, $b, false);
    }

    /**
     * a * int(b)
     */
    function mul_int(string $a, int $b): string
    {
        $sizeof = \strlen($a);

        // special cases
        if ($b === 0) {
            return \str_repeat("\0", $sizeof);
        }
        if ($b === 1) {
            return $a;
        }
        if ($b === -1) {
            return u\neg($a);
        }
        // overflow for the next handler
        if ($b === \PHP_INT_MIN || $b > i\MAX_MUL) {
            return i\_raw_mul($a, u\from_int($b, $sizeof), true);
        }
        // we handle only positive, but we can move the 'sign' to the left
        if ($b < 0) {
            return u\mul_int(u\neg($a), -$b);
        }

        $r = $a;
        $carry = 0;
        for ($i = 0; $i < $sizeof; ++$i) {
            $newChr = \ord($r[$i]) * $b + $carry;
            $r[$i] = \chr($newChr);
            $carry = $newChr >> 8;
        }

        return $r;
    }

    /**
     * a / b, a % b
     *
     * @return array [div -> string, mod -> string]
     */
    function div_mod(string $a, string $b, bool $forceSlow = false): array
    {
        $sizeof = \strlen($a);
        $sizeofb = \strlen($b);
        if ($sizeof !== $sizeofb) {
            throw new \DomainException("Arguments must be the same size, $sizeof and $sizeofb bytes given");
        }

        // special cases
        $zero = \str_repeat("\0", $sizeof);
        $compare = u\compare($a, $b);
        // if a < b, result is 0 and modulo is a
        if ($compare < 0) {
            return [$zero, $a];
        }
        $one = $zero;
        $one[0] = "\1";
        // if a = b, result is 1 and modulo is 0
        if ($compare === 0) {
            return [$one, $zero];
        }
        // 0
        if ($b === $zero) {
            throw new \RangeException('Division by zero');
        }
        // 1
        if ($b === $one) {
            return [$a, $zero];
        }
        // for pow2 just cut the required bits
        $b1 = u\add_int($b, -1);
        if (($b & $b1) === $zero) {
            $i = 0;
            while (!u\is_bit_set($b, $i)) {
                $i++;
            }
            return [
                u\shift_right($a, $i),
                $a & $b1,
            ];
        }
        // if we're lucky to have a small $b
        if (!$forceSlow && u\fits_into_int($b)) {
            $bi = u\to_int($b);
            if ($bi <= i\MAX_DIV) {
                list($div, $mod) = u\div_mod_int($a, u\to_int($b));
                return [
                    $div,
                    u\from_int($mod, $sizeof),
                ];
            }
        }

        $b = \rtrim($b, "\0"); // only significant bytes
        $bZero = $b . "\0";
        $bAdd = u\neg($bZero);
        $sizeofFrame = \strlen($b);

        $r = $zero;
        $m = $sizeofFrame === $sizeof ? $zero : \str_repeat("\0", $sizeofFrame);

        $i = $sizeof;
        while ($i--) {
            $m = $a[$i] . $m;
            $chr = 0;
            while (u\compare($m, $bZero) >= 0) {
                $m = u\add($m, $bAdd);
                $chr++;
            }
            $r[$i] = \chr($chr);
            $m = \substr($m, 0, $sizeofFrame);
        }

        return [$r, \str_pad($m, $sizeof, "\0")];
    }

    /**
     * a / int(b), a % int(b)
     *
     * @return array [div -> string, mod -> int]
     */
    function div_mod_int(string $a, int $b): array
    {
        $sizeof = \strlen($a);

        // can't handle negative, convert to unsigned first
        if ($b < 0) {
            throw new \DomainException(
                '$b must be greater than zero. Use div_mod($a, from_int($b)) for unsigned logic'
            );
        }
        // special cases
        if ($b === 0) {
            throw new \RangeException('Division by zero');
        }
        if ($b === 1) {
            return [$a, 0];
        }
        // fits into int, just calculate natively
        if (u\fits_into_int($a)) {
            $ai = u\to_int($a);
            return [u\from_int(\intdiv($ai, $b), $sizeof), $ai % $b];
        }
        // for pow2 just cut the required bits
        if (($b & ($b - 1)) === 0) {
            $i = 0;
            while (1 << $i !== $b) {
                $i++;
            }
            return [
                u\shift_right($a, $i),
                u\to_int($a & u\from_int($b - 1, $sizeof)),
            ];
        }
        // catch possible overflow
        if ($b > i\MAX_DIV) {
            $divmod = u\div_mod($a, u\from_int($b, $sizeof), true);
            return [$divmod[0], u\to_int($divmod[1])];
        }

        $mod = 0;
        $div = \str_repeat("\0", $sizeof);
        $i = $sizeof;
        while ($i--) {
            $dividend = $mod << 8 | \ord($a[$i]);
            $div[$i] = \chr(\intdiv($dividend, $b));
            $mod = $dividend % $b;
        }

        return [$div, $mod];
    }

    /**
     * a / b
     */
    function div(string $a, string $b): string
    {
        return u\div_mod($a, $b)[0];
    }

    /**
     * a / int(b)
     */
    function div_int(string $a, int $b): string
    {
        // can't handle negative, convert to unsigned first
        // custom message
        if ($b < 0) {
            throw new \DomainException(
                '$b must be greater than zero. Use div($a, from_int($b)) for unsigned logic'
            );
        }

        return u\div_mod_int($a, $b)[0];
    }

    /**
     * a % b
     */
    function mod(string $a, string $b): string
    {
        $sizeof = \strlen($a);
        $sizeofb = \strlen($b);
        if ($sizeof !== $sizeofb) {
            throw new \DomainException("Arguments must be the same size, $sizeof and $sizeofb bytes given");
        }

        // special cases
        $compare = u\compare($a, $b);
        // if a < b, entire a is modulo
        if ($compare < 0) {
            return $a;
        }
        $zero = \str_repeat("\0", $sizeof);
        // if a = b, modulo is 0
        if ($compare === 0) {
            return $zero;
        }
        // 0
        if ($b === $zero) {
            throw new \RangeException('Modulo by zero');
        }
        // 1
        $one = $zero;
        $one[0] = "\1";
        if ($b === $one) {
            return $zero;
        }
        // for pow2 just cut the required bits
        $b1 = u\add_int($b, -1);
        if (($b & $b1) === $zero) {
            return $a & $b1;
        }
        // if we're lucky to have a small $b
        if (u\fits_into_int($b)) {
            return u\from_int(u\mod_int($a, u\to_int($b)), $sizeof);
        }

        // do a slow algo
        return u\div_mod($a, $b)[1];
    }

    /**
     * a % int(b) -> int
     */
    function mod_int(string $a, int $b): int
    {
        $sizeof = \strlen($a);

        // can't handle negative, convert to unsigned first
        if ($b < 0) {
            throw new \DomainException(
                '$b must be greater than zero. Use mod($a, from_int($b)) for unsigned logic'
            );
        }
        // special cases
        if ($b === 0) {
            throw new \RangeException('Modulo by zero');
        }
        if ($b === 1) {
            return 0;
        }
        // fits into int, just calculate natively
        if (u\fits_into_int($a)) {
            $ai = u\to_int($a);
            return $ai % $b;
        }
        // for pow2 just cut the required bits
        if (($b & ($b - 1)) === 0) {
            return u\to_int($a & u\from_int($b - 1, $sizeof));
        }
        // catch possible overflow
        if ($b > i\MAX_DIV) {
            return u\to_int(u\div_mod($a, u\from_int($b, $sizeof), true)[1]);
        }

        $mod = 0;
        $i = $sizeof;
        while ($i--) {
            $dividend = $mod << 8 | \ord($a[$i]);
            $mod = $dividend % $b;
        }

        return $mod;
    }

    /**
     * a <=> b
     */
    function compare(string $a, string $b): int
    {
        $sizeof = \strlen($a);
        $sizeofb = \strlen($b);
        if ($sizeof !== $sizeofb) {
            throw new \DomainException("Arguments must be the same size, $sizeof and $sizeofb bytes given");
        }

        $i = $sizeof;
        while ($i--) {
            $compare = $a[$i] <=> $b[$i];
            if ($compare !== 0) {
                return $compare;
            }
        }
        return 0;
    }

    function set_bit(string $a, int $bit): string
    {
        $sizeof = \strlen($a);
        if ($bit < 0) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }
        if ($bit > $sizeof * 8) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }

        $byte = $bit >> 3;
        $bit &= 7;
        $bitmask = 1 << $bit;

        $a[$byte] = \chr(\ord($a[$byte]) | $bitmask);

        return $a;
    }

    function unset_bit(string $a, int $bit): string
    {
        $sizeof = \strlen($a);
        if ($bit < 0) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }
        if ($bit > $sizeof * 8) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }

        $byte = $bit >> 3;
        $bit &= 7;
        $bitmask = 1 << $bit;

        $a[$byte] = \chr(\ord($a[$byte]) & ~$bitmask);

        return $a;
    }

    function is_bit_set(string $a, int $bit): bool
    {
        $sizeof = \strlen($a);
        if ($bit < 0) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }
        if ($bit > $sizeof * 8) {
            throw new \DomainException("Bit must be in range 0-" . ($sizeof * 8 - 1));
        }

        $byte = $bit >> 3;
        $bit &= 7;
        $bitmask = 1 << $bit;

        return (\ord($a[$byte]) & $bitmask) !== 0;
    }
}

/** @internal */
namespace Arokettu\Unsigned\Internal
{
    use Arokettu\Unsigned as u;
    use Arokettu\Unsigned\Internal as i;

    /**
     * @internal
     */
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';
    /**
     * @internal
     */
    const DIGIT_VALUE = [
        0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'a' => 10, 'b' => 11, 'c' => 12,
        'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22,
        'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32,
        'x' => 33, 'y' => 34, 'z' => 35,
    ]; // array_flip(str_split(ALPHABET))

    /**
     * @internal
     * Max integer that can be added to a byte without overflowing
     */
    const MAX_ADD = \PHP_INT_MAX - 255;
    /**
     * @internal
     * Max integer that can be multiplied by a byte without overflowing (+ carry)
     */
    const MAX_MUL = (\PHP_INT_MAX >> 8) + 1;
    /**
     * @internal
     * Max dividend that can be shifted by a byte length without overflowing
     */
    const MAX_DIV = \PHP_INT_MAX >> 8;

    /**
     * @internal
     */
    function _raw_mul(string $a, string $b, bool $forceSlow): string
    {
        $sizeof = \strlen($a);
        $sizeofb = \strlen($b);
        if ($sizeof !== $sizeofb) {
            throw new \DomainException("Arguments must be the same size, $sizeof and $sizeofb bytes given");
        }
        // if we're lucky to have a small $a
        if (!$forceSlow && u\fits_into_int($a)) {
            $ai = u\to_int($a);
            if ($ai <= i\MAX_MUL) {
                return u\mul_int($b, $ai);
            }
        }
        // or $b
        if (!$forceSlow && u\fits_into_int($b)) {
            $bi = u\to_int($b);
            if ($bi <= i\MAX_MUL) {
                return u\mul_int($a, $bi);
            }
        }

        return \PHP_INT_SIZE >= 8 ? i\_raw_mul64($a, $b, $sizeof) : i\_raw_mul32($a, $b, $sizeof);
    }

    /**
     * @internal
     */
    function _raw_mul32(string $a, string $b, int $sizeof): string
    {
        $newval = \str_repeat("\0", $sizeof);

        for ($i = 0; $i < $sizeof; $i++) {
            $carry = 0;
            $ord = \ord($a[$i]);
            for ($j = 0; $j < $sizeof - $i; $j++) {
                $idx = $i + $j;

                $newChr = $ord * \ord($b[$j]) + \ord($newval[$idx]) + $carry;
                $newval[$idx] = \chr($newChr);
                $carry = $newChr >> 8;
            }
        }

        return $newval;
    }

    /**
     * @internal
     */
    function _raw_mul64(string $a, string $b, int $sizeof): string
    {
        // we can safely process 3 (PHP_INT_SIZE-1 div 2) on 64-bit systems

        $m = $sizeof % 3;
        $sizeofPadded = $m ? $sizeof + 3 - $m : $sizeof;

        $a = \str_pad($a, $sizeofPadded, "\0", \STR_PAD_RIGHT);
        $b = \str_pad($b, $sizeofPadded, "\0", \STR_PAD_RIGHT);
        $newval = \str_repeat("\0", $sizeofPadded);

        for ($i = 0; $i < $sizeof; $i += 3) {
            $carry = 0;
            $ordI = \ord($a[$i + 2]) << 16 | \ord($a[$i + 1]) << 8 | \ord($a[$i]);
            for ($j = 0; $j < $sizeof - $i; $j += 3) {
                $idx = $i + $j;
                $ordJ = \ord($b[$j + 2]) << 16 | \ord($b[$j + 1]) << 8 | \ord($b[$j]);
                $ordNewval = \ord($newval[$idx + 2]) << 16 | \ord($newval[$idx + 1]) << 8 | \ord($newval[$idx]);

                $newChr = $ordI * $ordJ + $ordNewval + $carry;
                $newval[$idx] = \chr($newChr);
                $newChr >>= 8;
                $newval[$idx + 1] = \chr($newChr);
                $newChr >>= 8;
                $newval[$idx + 2] = \chr($newChr);
                $carry = $newChr >> 8;
            }
        }

        return $sizeofPadded === $sizeof ? $newval : \substr($newval, 0, $sizeof);
    }
}
