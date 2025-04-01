<?php

/**
 * Extracted from arokettu/unsigned
 *
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Unsigned;

/**
 * @internal
 */
function from_int(int $value, int $sizeof): string
{
    return Unsigned::from_int($value, $sizeof);
}

/**
 * @internal
 */
function to_int(string $value): int
{
    return Unsigned::to_int($value);
}

/**
 * @internal
 */
function to_signed_int(string $value): int
{
    return Unsigned::to_signed_int($value);
}

/**
 * @internal
 */
function fits_into_int(string $value): bool
{
    return Unsigned::fits_into_int($value);
}

/**
 * @internal
 */
function from_hex(string $value, int $sizeof): string
{
    return Unsigned::from_hex($value, $sizeof);
}

/**
 * @internal
 */
function to_hex(string $value): string
{
    return Unsigned::to_hex($value);
}

/**
 * @internal
 */
function from_base(string $value, int $base, int $sizeof): string
{
    return Unsigned::from_base($value, $base, $sizeof);
}

/**
 * @internal
 */
function to_base(string $value, int $base): string
{
    return Unsigned::to_base($value, $base);
}

/**
 * @internal
 */
function from_dec(string $value, int $sizeof): string
{
    return Unsigned::from_dec($value, $sizeof);
}

/**
 * @internal
 */
function to_dec(string $value): string
{
    return Unsigned::to_dec($value);
}

/**
 * @internal
 */
function shift_left(string $value, int $shift): string
{
    return Unsigned::shift_left($value, $shift);
}

/**
 * @internal
 */
function shift_right(string $value, int $shift): string
{
    return Unsigned::shift_right($value, $shift);
}

/**
 * @internal
 */
function add(string $a, string $b): string
{
    return Unsigned::add($a, $b);
}

/**
 * @internal
 */
function add_int(string $a, int $b): string
{
    return Unsigned::add_int($a, $b);
}

/**
 * @internal
 */
function sub(string $a, string $b): string
{
    return Unsigned::sub($a, $b);
}

/**
 * @internal
 */
function sub_int(string $a, int $b): string
{
    return Unsigned::sub_int($a, $b);
}

/**
 * @internal
 */
function sub_int_rev(int $a, string $b): string
{
    return Unsigned::sub_int_rev($a, $b);
}

/**
 * @internal
 */
function neg(string $a): string
{
    return Unsigned::neg($a);
}

/**
 * @internal
 */
function mul(string $a, string $b): string
{
    return Unsigned::mul($a, $b);
}

/**
 * @internal
 */
function mul_int(string $a, int $b): string
{
    return Unsigned::mul_int($a, $b);
}

/**
 * @internal
 */
function div_mod(string $a, string $b): array
{
    return Unsigned::div_mod($a, $b);
}

/**
 * @internal
 */
function div_mod_int(string $a, int $b): array
{
    return Unsigned::div_mod_int($a, $b);
}

/**
 * @internal
 */
function div(string $a, string $b): string
{
    return Unsigned::div($a, $b);
}

/**
 * @internal
 */
function div_int(string $a, int $b): string
{
    return Unsigned::div_int($a, $b);
}

/**
 * @internal
 */
function mod(string $a, string $b): string
{
    return Unsigned::mod($a, $b);
}

/**
 * @internal
 */
function mod_int(string $a, int $b): int
{
    return Unsigned::mod_int($a, $b);
}

/**
 * @internal
 */
function compare(string $a, string $b): int
{
    return Unsigned::compare($a, $b);
}

/**
 * @internal
 */
function set_bit(string $a, int $bit): string
{
    return Unsigned::set_bit($a, $bit);
}

/**
 * @internal
 */
function unset_bit(string $a, int $bit): string
{
    return Unsigned::unset_bit($a, $bit);
}

/**
 * @internal
 */
function is_bit_set(string $a, int $bit): bool
{
    return Unsigned::is_bit_set($a, $bit);
}
