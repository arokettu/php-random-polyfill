<?php

declare(strict_types=1);

namespace Random;

use GMP;
use Random\Engine\Mt19937;
use Random\Engine\Secure;
use RuntimeException;
use ValueError;

use function gmp_import;
use function gmp_init;
use function gmp_intval;
use function str_pad;
use function strlen;
use function substr;

use const GMP_LITTLE_ENDIAN;
use const MT_RAND_PHP;

final class Randomizer
{
    private const SIZEOF_UINT_64_T = 8;
    private const SIZEOF_UINT_32_T = 4;
    private const PHP_MT_RAND_MAX = 0x7FFFFFFF;

    /** @var Engine */
    private $engine;
    /** @var GMP|null */
    private static $UINT32_MAX = null;
    /** @var GMP|null */
    private static $UINT64_MAX = null;

    public function __construct(?Engine $engine = null)
    {
        $this->initConst();

        $this->engine = $engine ?? new Engine\Secure();
    }

    private function initConst(): void
    {
        if (self::$UINT32_MAX === null) {
            self::$UINT32_MAX = gmp_init('ffff' . 'ffff', 16);
        }
        if (self::$UINT64_MAX === null) {
            self::$UINT64_MAX = gmp_init('ffff' . 'ffff' . 'ffff' . 'ffff', 16);
        }
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

        // handle MT_RAND_PHP
        //
        // phpcs:disable Squiz.Functions.MultiLineFunctionDeclaration.ContentAfterBrace
        // phpcs:disable Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore
        /** @psalm-suppress UndefinedThisPropertyFetch */
        if (
            $this->engine instanceof Mt19937 &&
            (function () { return $this->mode === MT_RAND_PHP; })->call($this->engine) // read private property
        ) {
            return $this->rangeBadscaling($min, $max);
        }
        // phpcs:enable Squiz.Functions.MultiLineFunctionDeclaration.ContentAfterBrace
        // phpcs:enable Squiz.WhiteSpace.ScopeClosingBrace.ContentBefore

        $umax = gmp_init($max) - gmp_init($min);

        $bit32 =
            $this->engine instanceof Mt19937 ||
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

        if ($umax == self::$UINT32_MAX) {
            return $result;
        }

        $umax += 1;

        if (($umax & ($umax - 1)) == 0) {
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

        if ($umax == self::$UINT64_MAX) {
            return $result;
        }

        $umax += 1;

        if (($umax & ($umax - 1)) == 0) {
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

    private function rangeBadscaling(int $min, int $max): int
    {
        $n = $this->generate();
        $n = $this->importGmp32($n);
        $n = gmp_intval($n >> 1);
        // (__n) = (__min) + (zend_long) ((double) ( (double) (__max) - (__min) + 1.0) * ((__n) / ((__tmax) + 1.0)))
        return $min + (int) (( (float)$max - $min + 1.0) * ($n / (self::PHP_MT_RAND_MAX + 1.0)));
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

    public function __wakeup(): void
    {
        $this->initConst();
    }
}
