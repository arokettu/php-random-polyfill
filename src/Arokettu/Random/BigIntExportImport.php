<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random;

use GMP;

use function gmp_export;
use function gmp_import;
use function gmp_init;
use function str_pad;
use function substr;

use const GMP_LITTLE_ENDIAN;
use const GMP_LSW_FIRST;

/**
 * @internal
 */
trait BigIntExportImport
{
    /**
     * @var int
     * @psalm-var positive-int
     */
    private static $SIZEOF_UINT32_T = 4;
    /**
     * @var int
     * @psalm-var positive-int
     */
    private static $SIZEOF_UINT64_T = 8;
    /**
     * @var int
     * @psalm-var positive-int
     */
    private static $SIZEOF_UINT128_T = 16;

    /** @var GMP|null 32-bit bitmask aka max 32-bit uint */
    private static $UINT32_MASK = null;
    /** @var GMP|null 64-bit bitmask aka max 64-bit uint */
    private static $UINT64_MASK = null;
    /** @var GMP|null 128-bit bitmask aka max 128-bit uint */
    private static $UINT128_MASK = null;

    private function initGmpConst(): void
    {
        if (self::$UINT32_MASK === null) {
            self::$UINT32_MASK = gmp_init('ffffffff', 16);
        }
        if (self::$UINT64_MASK === null) {
            self::$UINT64_MASK = gmp_init('ffffffffffffffff', 16);
        }
        if (self::$UINT128_MASK === null) {
            self::$UINT128_MASK = gmp_init('ffffffffffffffffffffffffffffffff', 16);
        }
    }

    private function importGmp32(string $value): GMP
    {
        $value = substr($value, 0, self::$SIZEOF_UINT32_T);
        $value = str_pad($value, self::$SIZEOF_UINT32_T, "\0");
        return gmp_import($value, self::$SIZEOF_UINT32_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }

    private function importGmp64(string $value): GMP
    {
        $value = substr($value, 0, self::$SIZEOF_UINT64_T);
        $value = str_pad($value, self::$SIZEOF_UINT64_T, "\0");
        return gmp_import($value, self::$SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }

    private function importGmp128hilo(string $hi, string $lo): GMP
    {
        $value = $lo . $hi;
        $value = substr($value, 0, self::$SIZEOF_UINT128_T);
        $value = str_pad($value, self::$SIZEOF_UINT128_T, "\0");
        return gmp_import($value, self::$SIZEOF_UINT128_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }

    private function exportGmp32(GMP $value): string
    {
        $value = $value & self::$UINT32_MASK;
        $value = gmp_export($value, self::$SIZEOF_UINT32_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
        return str_pad($value, self::$SIZEOF_UINT32_T, "\0");
    }

    private function exportGmp64(GMP $value): string
    {
        $value = $value & self::$UINT64_MASK;
        $value = gmp_export($value, self::$SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
        return str_pad($value, self::$SIZEOF_UINT64_T, "\0");
    }

    private function exportGmp128hilo(GMP $value): array
    {
        $value = $value & self::$UINT128_MASK;
        $value = gmp_export($value, self::$SIZEOF_UINT128_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
        $value = str_pad($value, self::$SIZEOF_UINT128_T, "\0");
        return array_reverse(str_split($value, self::$SIZEOF_UINT64_T));
    }
}
