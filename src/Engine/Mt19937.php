<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes adaptation of C code from the PHP Interpreter
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 * @see https://github.com/php/php-src/blob/master/ext/random/engine_mt19937.c
 */

declare(strict_types=1);

namespace Random\Engine;

use Exception;
use GMP;
use InvalidArgumentException;
use Random\Engine;
use RuntimeException;
use Serializable;

use function array_fill;
use function array_map;
use function bin2hex;
use function gmp_export;
use function gmp_import;
use function gmp_init;
use function hex2bin;
use function random_int;

use const MT_RAND_MT19937;
use const MT_RAND_PHP;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

final class Mt19937 implements Engine, Serializable
{
    private const N = 624;
    private const M = 397;
    private const N_M = self::N - self::M;

    /** @var GMP[] */
    private $state;
    /** @var int */
    private $stateCount;
    /** @var int */
    private $mode;

    /** @var GMP|null  */
    private static $TWIST_CONST = null;
    /** @var GMP|null  */
    private static $BIT32 = null;
    /** @var GMP|null  */
    private static $SEED_STEP_VALUE = null;
    /** @var GMP|null  */
    private static $HI_BIT = null;
    /** @var GMP|null  */
    private static $LO_BIT = null;
    /** @var GMP|null  */
    private static $LO_BITS = null;
    /** @var GMP|null  */
    private static $GEN1 = null;
    /** @var GMP|null  */
    private static $GEN2 = null;

    public function __construct(?int $seed = null, int $mode = MT_RAND_MT19937)
    {
        $this->initConst();

        if ($mode !== MT_RAND_PHP && $mode !== MT_RAND_MT19937) {
            throw new InvalidArgumentException('Argument #2 ($mode) mode must be MT_RAND_MT19937 or MT_RAND_PHP');
        }
        $this->mode = $mode;

        try {
            $this->seed($seed ?? random_int(PHP_INT_MIN, PHP_INT_MAX));
        } catch (Exception $e) {
            throw new RuntimeException('Random number generation failed');
        }
    }

    private function initConst(): void
    {
        if (self::$TWIST_CONST === null) {
            self::$TWIST_CONST = gmp_init('9908b0df', 16); // can't fit into signed 32bit int
        }
        if (self::$BIT32 === null) {
            self::$BIT32 = gmp_init('ffffffff', 16); // can't fit into signed 32bit int
        }
        if (self::$SEED_STEP_VALUE === null) {
            self::$SEED_STEP_VALUE = gmp_init(1812433253); // can fit into signed 32bit int
        }
        if (self::$HI_BIT === null) {
            self::$HI_BIT = gmp_init('80000000', 16); // can't fit into signed 32bit int
        }
        if (self::$LO_BIT === null) {
            self::$LO_BIT = gmp_init(1); // can fit into signed 32bit int
        }
        if (self::$LO_BITS === null) {
            self::$LO_BITS = gmp_init(0x7FFFFFFF); // can fit into signed 32bit int
        }
        if (self::$GEN1 === null) {
            self::$GEN1 = gmp_init('9d2c5680', 16); // can't fit into signed 32bit int
        }
        if (self::$GEN2 === null) {
            self::$GEN2 = gmp_init('efc60000', 16); // can't fit into signed 32bit int
        }
    }

    private function seed(int $seed): void
    {
        /** @var GMP[] $state */
        $state = array_fill(0, self::N, null);

        $prevState = $state[0] = $seed & self::$BIT32;
        for ($i = 1; $i < self::N; $i++) {
            $prevState = $state[$i] = (self::$SEED_STEP_VALUE * ($prevState ^ ($prevState >> 30)) + $i) & self::$BIT32;
        }

        $this->state = $state;
        $this->stateCount = $i;

        $this->reload();
    }

    private function reload(): void
    {
        $p = 0;
        $s = &$this->state;

        for ($i = self::N_M; $i--; ++$p) {
            $s[$p] = $this->twist($s[$p + self::M], $s[$p], $s[$p + 1]);
        }
        for ($i = self::M; --$i; ++$p) {
            $s[$p] = $this->twist($s[$p - self::N_M], $s[$p], $s[$p + 1]);
        }
        $s[$p] = $this->twist($s[$p - self::N_M], $s[$p], $s[0]);

        $this->stateCount = 0;
    }

    private function twist(GMP $m, GMP $u, GMP $v): GMP
    {
        // this brain explosion:
        // #define twist(m,u,v)  (m ^ (mixBits(u,v) >> 1) ^ ((uint32_t)(-(int32_t)(loBit(v))) & 0x9908b0dfU))
        // #define twist_php(m,u,v)  (m ^ (mixBits(u,v) >> 1) ^ ((uint32_t)(-(int32_t)(loBit(u))) & 0x9908b0dfU))

        $mixBits = ($u & self::$HI_BIT | $v & self::$LO_BITS) >> 1;

        if ($this->mode === MT_RAND_MT19937) {
            $twist = gmp_intval($v & self::$LO_BIT) ? self::$TWIST_CONST : 0;
        } else {
            $twist = gmp_intval($u & self::$LO_BIT) ? self::$TWIST_CONST : 0;
        }

        return $m ^ $mixBits ^ $twist;
    }

    public function generate(): string
    {
        if ($this->stateCount >= self::N) {
            $this->reload();
        }

        $s1 = $this->state[$this->stateCount++];
        $s1 ^= ($s1 >> 11);
        $s1 ^= ($s1 << 7) & self::$GEN1;
        $s1 ^= ($s1 << 15) & self::$GEN2;
        $s1 ^= ($s1 >> 18);

        return gmp_export($s1, 4, GMP_LITTLE_ENDIAN);
    }

    public function serialize(): string
    {
        trigger_error('Serialized object will be incompatible with PHP 8.2', E_USER_WARNING);
        return serialize($this->__serialize());
    }

    /**
     * @param string $data
     * @throws Exception
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function unserialize($data): void
    {
        $this->__unserialize(unserialize($data));
    }

    public function __serialize(): array
    {
        return [[], $this->getStates()];
    }

    /**
     * @throws Exception
     */
    public function __unserialize(array $data): void
    {
        $this->initConst();
        $this->loadStates($data[1] ?? []);
    }

    public function __debugInfo(): array
    {
        return ['__states' => $this->getStates()];
    }

    private function getStates(): array
    {
        $states = array_map(function (GMP $gmp) {
            return bin2hex(gmp_export($gmp, 4, GMP_LITTLE_ENDIAN));
        }, $this->state);
        $states[] = $this->stateCount;
        $states[] = $this->mode;

        return $states;
    }

    /**
     * @throws Exception
     */
    private function loadStates(array $states): void
    {
        /** @var GMP[] $state */
        $state = array_fill(0, self::N, null);

        for ($i = 0; $i < self::N; $i++) {
            if (!isset($states[$i])) {
                throw new Exception("Engine serialize failed");
            }
            $state[$i] = gmp_import(hex2bin($states[$i]), 4, GMP_LITTLE_ENDIAN);
        }

        $this->state = $state;
        $this->stateCount = $states[self::N];
        $this->mode = $states[self::N + 1];
    }
}
