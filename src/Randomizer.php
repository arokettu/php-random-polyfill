<?php

declare(strict_types=1);

namespace Random;

use GMP;
use Random\Engine\Secure;
use RuntimeException;
use ValueError;

use function gmp_import;
use function gmp_init;
use function str_pad;
use function strlen;
use function substr;

use const GMP_LITTLE_ENDIAN;

final class Randomizer
{
    private const SIZEOF_UINT_64_T = 8;
    private const SIZEOF_UINT_32_T = 4;

    /** @var Engine|null */
    private $engine;
    /** @var GMP */
    private static $UINT32_MAX = null;
    private static $UINT64_MAX = null;

    public function __construct(?Engine $engine = null)
    {
        $this->engine = $engine ?? new Engine\Secure();
        self::$UINT32_MAX = self::$UINT32_MAX ?? gmp_init('4294967295');
        self::$UINT64_MAX = self::$UINT64_MAX ?? gmp_init('18446744073709551615');
    }

    private function generate(): string
    {
        $retval = $this->engine->generate();

        $size = strlen($retval);

        if ($size === 0) {
            throw new RuntimeException('Random number generation failed');
        } elseif ($size > self::SIZEOF_UINT_64_T) {
            $retval = substr($retval, 0, self::SIZEOF_UINT_64_T);
        }

        return $retval;
    }

    public function getInt(int ...$args): int
    {
        if ($args === []) {
            return $this->doNextInt();
        }

        return $this->doGetInt(...$args);
    }

    private function doGetInt(int $min, int $max): int
    {
        if ($max < $min) {
            throw new ValueError('Argument #2 ($max) must be greater than or equal to argument #1 ($min)');
        }

        $umax = gmp_init($max) - gmp_init($min);

        $bit32 =
            $this->engine instanceof Secure && PHP_INT_SIZE <= self::SIZEOF_UINT_32_T ||
            $umax > self::$UINT32_MAX;

        return gmp_intval(($bit32 ? $this->range32($umax) : $this->range64($umax)) + $min);
    }

    private function range32(GMP $umax): GMP
    {
        $result = $this->generate();
        if (strlen($result) < self::SIZEOF_UINT_32_T) {
            $result .= $this->generate();
        }

        $result = $this->importGmp32($result);

        if ($umax === self::$UINT32_MAX) {
            return $result;
        }

        $umax += 1;

        if (($umax & ($umax - 1)) === 0) {
            return $result & ($umax - 1);
        }

        $limit = self::$UINT32_MAX - (self::$UINT32_MAX % $umax) - 1;

        $count = 0;

        while ($result > $limit) {
            if (++$count > 50) {
                throw new RuntimeException('Random number generation failed');
            }

            $result = $this->generate();
            if (strlen($result) < self::SIZEOF_UINT_32_T) {
                $result .= $this->generate();
            }

            $result = $this->importGmp32($result);
        }

        return $result % $umax;
    }

    private function range64(GMP $umax): GMP
    {
        $result = $this->generate();
        if (strlen($result) < self::SIZEOF_UINT_64_T) {
            $result .= $this->generate();
        }

        $result = $this->importGmp64($result);

        if ($umax === self::$UINT64_MAX) {
            return $result;
        }

        $umax += 1;

        if (($umax & ($umax - 1)) === 0) {
            return $result & ($umax - 1);
        }

        $limit = self::$UINT64_MAX - (self::$UINT64_MAX % $umax) - 1;

        $count = 0;

        while ($result > $limit) {
            if (++$count > 50) {
                throw new RuntimeException('Random number generation failed');
            }

            $result = $this->generate();
            if (strlen($result) < self::SIZEOF_UINT_64_T) {
                $result .= $this->generate();
            }

            $result = $this->importGmp64($result);
        }

        return $result % $umax;
    }

    private function doNextInt(): int
    {
        $result = $this->generate();
        $result = $this->importGmp64($result);

        return gmp_intval($result >> 1);
    }

    private function importGmp32(string $value): GMP
    {
        $value = substr($value, 0, self::SIZEOF_UINT_32_T);
        $value = str_pad($value, self::SIZEOF_UINT_32_T, "\0");
        return gmp_import($value, self::SIZEOF_UINT_32_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }

    private function importGmp64(string $value): GMP
    {
        $value = substr($value, 0, self::SIZEOF_UINT_64_T);
        $value = str_pad($value, self::SIZEOF_UINT_64_T, "\0");
        return gmp_import($value, self::SIZEOF_UINT_64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST);
    }
}
