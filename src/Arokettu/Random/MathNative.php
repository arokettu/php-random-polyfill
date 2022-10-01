<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * @noinspection PhpMissingReturnTypeInspection
 */

declare(strict_types=1);

namespace Arokettu\Random;

/**
 * @internal
 * @psalm-suppress MoreSpecificImplementedParamType
 * @codeCoverageIgnore We don't care about math that was not used
 */
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
class MathNative extends Math
{
    /** @var int */
    private $mask;
    /** @var int */
    private $halfMask;
    /** @var int */
    private $sizeof;

    /**
     * @param int $sizeof
     */
    public function __construct(int $sizeof)
    {
        $this->mask = (2 ** ($sizeof * 8)) - 1;
        $this->halfMask = (2 ** ($sizeof * 4)) - 1;
        $this->sizeof = $sizeof;
    }

    /**
     * @param int $value
     * @param int $shift
     * @return int
     */
    public function shiftLeft($value, int $shift)
    {
        return ($value << $shift) & $this->mask;
    }

    /**
     * @param int $value
     * @param int $shift
     * @return int
     */
    public function shiftRight($value, int $shift)
    {
        return $value >> $shift;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function add($value1, $value2)
    {
        return ($value1 + $value2) & $this->mask;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function addInt($value1, int $value2)
    {
        return ($value1 + $value2) & $this->mask;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function sub($value1, $value2)
    {
        return ($value1 - $value2) & $this->mask;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function subInt($value1, int $value2)
    {
        return ($value1 - $value2) & $this->mask;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function mul($value1, $value2)
    {
        // do some crazy stuff to avoid overflow larger than a byte
        // split like splitHiLo but do not create an array
        $halfBits = $this->sizeof * 4;

        $v1hi = $value1 >> $halfBits;
        $v2hi = $value2 >> $halfBits;
        $v1lo = $value1 & $this->halfMask;
        $v2lo = $value2 & $this->halfMask;

        $hi = $v1hi * $v2lo + $v1lo * $v2hi;
        $lo = $v1lo * $v2lo;

        return (($hi << $halfBits) + $lo) & $this->mask;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function mulInt($value1, int $value2)
    {
        return $this->mul($value1, $value2);
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function mod($value1, $value2)
    {
        return $value1 % $value2;
    }

    /**
     * @param int $value1
     * @param int $value2
     * @return int
     */
    public function compare($value1, $value2): int
    {
        return $value1 <=> $value2;
    }

    /**
     * @param string $value
     * @return int
     */
    public function fromHex(string $value)
    {
        return \hexdec($value);
    }

    /**
     * @param int $value
     * @return int
     */
    public function fromInt(int $value)
    {
        return $value & $this->mask;
    }

    /**
     * @param string $value
     * @return int
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

        return $this->fromHex(\bin2hex(\strrev($value)));
    }

    /**
     * @param int $value
     */
    public function toInt($value): int
    {
        return $value;
    }

    /**
     * @param int $value
     */
    public function toSignedInt($value): int
    {
        if ($value & 1 << ($this->sizeof * 8 - 1)) { // sign
            $value -= 1 << $this->sizeof * 8;
        }

        return $value;
    }

    /**
     * @param int $value
     */
    public function toBinary($value): string
    {
        $hex = \dechex($value);
        $bin = \hex2bin(\strlen($hex) % 2 ? '0' . $hex : $hex);
        return \str_pad(\strrev($bin), $this->sizeof, "\0");
    }

    /**
     * @param int $value
     * @return int[]
     */
    public function splitHiLo($value): array
    {
        return [
            $value >> ($this->sizeof * 8 / 2),
            $value & $this->halfMask,
        ];
    }
}
