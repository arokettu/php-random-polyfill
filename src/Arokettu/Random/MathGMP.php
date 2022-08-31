<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * @noinspection PhpComposerExtensionStubsInspection
 * @noinspection PhpReturnDocTypeMismatchInspection
 * @noinspection PhpIncompatibleReturnTypeInspection
 */

declare(strict_types=1);

namespace Arokettu\Random;

use GMP;

/**
 * @internal
 * @psalm-suppress MoreSpecificImplementedParamType
 * @codeCoverageIgnore We don't care about math that was not used
 */
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
final class MathGMP extends Math
{
    /** @var GMP */
    private $mask;
    /** @var int */
    private $sizeof;

    /**
     * @param int $sizeof
     */
    public function __construct(int $sizeof)
    {
        $this->mask = \gmp_pow(2, $sizeof * 8) - 1;
        $this->sizeof = $sizeof;
    }

    /**
     * @param GMP $value
     * @param int $shift
     * @return GMP
     */
    public function shiftLeft($value, int $shift)
    {
        return ($value << $shift) & $this->mask;
    }

    /**
     * @param GMP $value
     * @param int $shift
     * @return GMP
     */
    public function shiftRight($value, int $shift)
    {
        return $value >> $shift;
    }

    /**
     * @param GMP $value1
     * @param GMP $value2
     * @return GMP
     */
    public function add($value1, $value2)
    {
        return ($value1 + $value2) & $this->mask;
    }

    /**
     * @param GMP $value1
     * @param int $value2
     * @return GMP
     */
    public function addInt($value1, int $value2)
    {
        return ($value1 + $value2) & $this->mask;
    }

    /**
     * @param GMP $value1
     * @param GMP $value2
     * @return GMP
     */
    public function sub($value1, $value2)
    {
        return ($value1 - $value2) & $this->mask;
    }

    /**
     * @param GMP $value1
     * @param int $value2
     * @return GMP
     */
    public function subInt($value1, int $value2)
    {
        return ($value1 - $value2) & $this->mask;
    }

    /**
     * @param GMP $value1
     * @param GMP $value2
     * @return GMP
     */
    public function mul($value1, $value2)
    {
        return ($value1 * $value2) & $this->mask;
    }

    /**
     * @param GMP $value1
     * @param GMP $value2
     * @return GMP
     */
    public function mod($value1, $value2)
    {
        return $value1 % $value2;
    }

    /**
     * @param GMP $value1
     * @param GMP $value2
     * @return int
     */
    public function compare($value1, $value2): int
    {
        return $value1 <=> $value2;
    }

    /**
     * @param string $value
     * @return GMP
     */
    public function fromHex(string $value)
    {
        return \gmp_init($value, 16);
    }

    /**
     * @param int $value
     * @return GMP
     */
    public function fromInt(int $value)
    {
        return $value & $this->mask;
    }

    /**
     * @param string $value
     * @return GMP
     */
    public function fromBinary(string $value)
    {
        switch (\strlen($value) <=> $this->sizeof) {
            case -1:
                $value = \str_pad($value, $this->sizeof, "\0");
                break;

            case 1:
                $value = \substr($value, 0, $this->sizeof);
        }

        return \gmp_import($value, $this->sizeof, \GMP_LITTLE_ENDIAN | \GMP_LSW_FIRST);
    }

    /**
     * @param GMP $value
     */
    public function toInt($value): int
    {
        return \gmp_intval($value);
    }

    /**
     * @param GMP $value
     */
    public function toSignedInt($value): int
    {
        if (($value & 1 << ($this->sizeof * 8 - 1)) != 0) { // sign
            $value -= \gmp_pow(2, $this->sizeof * 8);
        }

        return \gmp_intval($value);
    }

    /**
     * @param GMP $value
     */
    public function toBinary($value): string
    {
        // gmp_export returns empty string for zero, we should return exact bytes as sizeof
        return \str_pad(\gmp_export($value, $this->sizeof, \GMP_LITTLE_ENDIAN | \GMP_LSW_FIRST), $this->sizeof, "\0");
    }

    /**
     * @param GMP $value
     * @return GMP[]
     */
    public function splitHiLo($value): array
    {
        // A lot of assumptions about correct usage here
        $halfSize = $this->sizeof >> 1;
        /** @var self $halfMath */
        $halfMath = Math::create($halfSize);

        return [
            $value >> ($halfSize * 8),
            $value & $halfMath->mask,
        ];
    }
}
