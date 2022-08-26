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
use InvalidArgumentException;

use function gmp_import;
use function gmp_pow;
use function strlen;

use const GMP_LITTLE_ENDIAN;
use const GMP_LSW_FIRST;

/**
 * @internal
 * @psalm-suppress MoreSpecificImplementedParamType
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
        $this->mask = gmp_pow(2, $sizeof * 8) - 1;
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
     * @param GMP $value2
     * @return GMP
     */
    public function mul($value1, $value2)
    {
        return ($value1 * $value2) & $this->mask;
    }

    /**
     * @param string $value
     * @return GMP
     */
    public function fromHex(string $value)
    {
        return gmp_init($value, 16);
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
        if (strlen($value) !== $this->sizeof) {
            throw new InvalidArgumentException("Value must be {$this->sizeof} bytes long");
        }

        return gmp_import($value, $this->sizeof, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }

    /**
     * @param GMP $value
     */
    public function toBinary($value): string
    {
        return str_pad(gmp_export($value, $this->sizeof, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST), $this->sizeof, "\0");
    }
}
