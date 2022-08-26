<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * @noinspection PhpMissingReturnTypeInspection
 */

declare(strict_types=1);

namespace Arokettu\Random;

use InvalidArgumentException;

use function Arokettu\Unsigned\add;
use function Arokettu\Unsigned\from_hex;
use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\mul;
use function Arokettu\Unsigned\shift_left;
use function Arokettu\Unsigned\shift_right;
use function Arokettu\Unsigned\to_int;
use function str_split;
use function strlen;

/**
 * @internal
 * @psalm-suppress MoreSpecificImplementedParamType
 */
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
final class MathUnsigned extends Math
{
    /** @var int */
    private $sizeof;

    /**
     * @param int $sizeof
     */
    public function __construct(int $sizeof)
    {
        $this->sizeof = $sizeof;
    }

    /**
     * @param string $value
     * @param int $shift
     * @return string
     */
    public function shiftLeft($value, int $shift)
    {
        return shift_left($value, $shift);
    }

    /**
     * @param string $value
     * @param int $shift
     * @return string
     */
    public function shiftRight($value, int $shift)
    {
        return shift_right($value, $shift);
    }

    /**
     * @param string $value1
     * @param string $value2
     * @return string
     */
    public function add($value1, $value2)
    {
        return add($value1, $value2);
    }

    /**
     * @param string $value1
     * @param string $value2
     * @return string
     */
    public function mul($value1, $value2)
    {
        return mul($value1, $value2);
    }

    /**
     * @param string $value
     * @return string
     */
    public function fromHex(string $value)
    {
        return from_hex($value, $this->sizeof);
    }

    /**
     * @param int $value
     * @return string
     */
    public function fromInt(int $value)
    {
        return from_int($value, $this->sizeof);
    }

    /**
     * @param string $value
     * @return string
     */
    public function fromBinary(string $value)
    {
        if (strlen($value) !== $this->sizeof) {
            throw new InvalidArgumentException("Value must be {$this->sizeof} bytes long");
        }

        return $value;
    }

    /**
     * @param string $value
     */
    public function toInt($value): int
    {
        return to_int($value);
    }

    /**
     * @param string $value
     */
    public function toBinary($value): string
    {
        return $value;
    }

    /**
     * @param string $value
     * @return string[]
     */
    public function splitHiLo($value): array
    {
        /** @psalm-suppress PossiblyInvalidArrayAccess */
        [$lo, $hi] = str_split($value, $this->sizeof >> 1);
        return [$hi, $lo];
    }
}
