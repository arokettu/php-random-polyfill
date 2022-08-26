<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes adaptation of C code from the PHP Interpreter
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 * @see https://github.com/php/php-src/blob/master/ext/random/engine_mt19937.c
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Random\Engine;

use Arokettu\Random\Math;
use Arokettu\Random\NoDynamicProperties;
use Arokettu\Random\Serialization;
use Exception;
use GMP;
use Random\Engine;
use RuntimeException;
use Serializable;
use ValueError;

use function array_fill;
use function array_map;
use function bin2hex;
use function hex2bin;
use function random_int;

use const MT_RAND_MT19937;
use const MT_RAND_PHP;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

/*
    The following mt19937 algorithms are based on a C++ class MTRand by
    Richard J. Wagner. For more information see the web page at
    http://www.math.sci.hiroshima-u.ac.jp/~m-mat/MT/VERSIONS/C-LANG/MersenneTwister.h

    Mersenne Twister random number generator -- a C++ class MTRand
    Based on code by Makoto Matsumoto, Takuji Nishimura, and Shawn Cokus
    Richard J. Wagner  v1.0  15 May 2003  rjwagner@writeme.com

    The Mersenne Twister is an algorithm for generating random numbers.  It
    was designed with consideration of the flaws in various other generators.
    The period, 2^19937-1, and the order of equidistribution, 623 dimensions,
    are far greater.  The generator is also fast; it avoids multiplication and
    division, and it benefits from caches and pipelines.  For more information
    see the inventors' web page at http://www.math.sci.hiroshima-u.ac.jp/~m-mat/MT/emt.html

    Reference
    M. Matsumoto and T. Nishimura, "Mersenne Twister: A 623-Dimensionally
    Equidistributed Uniform Pseudo-Random Number Generator", ACM Transactions on
    Modeling and Computer Simulation, Vol. 8, No. 1, January 1998, pp 3-30.

    Copyright (C) 1997 - 2002, Makoto Matsumoto and Takuji Nishimura,
    Copyright (C) 2000 - 2003, Richard J. Wagner
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:

    1. Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.

    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.

    3. The names of its contributors may not be used to endorse or promote
       products derived from this software without specific prior written
       permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
    LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
    A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT OWNER OR
    CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
    EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
    PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
    PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
    LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
    NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

final class Mt19937 implements Engine, Serializable
{
    use NoDynamicProperties;
    use Serialization;

    private const N = 624;
    private const M = 397;
    private const N_M = self::N - self::M;

    /** @var GMP[]|string[]|int[] */
    private $state;
    /** @var int */
    private $stateCount;
    /** @var int */
    private $mode;

    /** @var Math */
    private static $math;

    /** @var GMP|string|int */
    private static $TWIST_CONST;
    /** @var GMP|string|int */
    private static $SEED_STEP_VALUE;
    /** @var GMP|string|int */
    private static $HI_BIT;
    /** @var GMP|string|int */
    private static $LO_BIT;
    /** @var GMP|string|int */
    private static $LO_BITS;
    /** @var GMP|string|int */
    private static $GEN1;
    /** @var GMP|string|int */
    private static $GEN2;
    /** @var GMP|string|int */
    private static $ZERO;

    public function __construct(?int $seed = null, int $mode = MT_RAND_MT19937)
    {
        $this->initMath();

        if ($mode !== MT_RAND_PHP && $mode !== MT_RAND_MT19937) {
            throw new ValueError(__METHOD__ . '(): Argument #2 ($mode) must be either MT_RAND_MT19937 or MT_RAND_PHP');
        }
        $this->mode = $mode;

        try {
            $this->seed($seed ?? random_int(PHP_INT_MIN, PHP_INT_MAX));
        } catch (Exception $e) {
            throw new RuntimeException('Random number generation failed');
        }
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     * @psalm-suppress DocblockTypeContradiction the "constants" are initialized here
     */
    private function initMath(): void
    {
        if (self::$math === null) {
            self::$math = Math::create(Math::SIZEOF_UINT32_T);

            self::$TWIST_CONST      = self::$math->fromHex('9908b0df'); // can't fit into signed 32bit int
            self::$SEED_STEP_VALUE  = self::$math->fromInt(1812433253); // can fit into signed 32bit int
            self::$HI_BIT           = self::$math->fromHex('80000000'); // can't fit into signed 32bit int
            self::$LO_BIT           = self::$math->fromInt(1); // can fit into signed 32bit int
            self::$LO_BITS          = self::$math->fromInt(0x7FFFFFFF); // can fit into signed 32bit int
            self::$GEN1             = self::$math->fromHex('9d2c5680'); // can't fit into signed 32bit int
            self::$GEN2             = self::$math->fromHex('efc60000'); // can't fit into signed 32bit int

            self::$ZERO             = self::$math->fromInt(0);
        }
    }

    private function seed(int $seed): void
    {
        /** @var GMP[]|string[]|int[] $state */
        $state = array_fill(0, self::N, null);

        $prevState = $state[0] = self::$math->fromInt($seed);
        for ($i = 1; $i < self::N; $i++) {
            $prevState = $state[$i] =
                self::$math->add(
                    self::$math->mul(
                        self::$SEED_STEP_VALUE,
                        $prevState ^ self::$math->shiftRight($prevState, 30)
                    ),
                    self::$math->fromInt($i)
                );
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

    /**
     * @param GMP|string|int $m
     * @param GMP|string|int $u
     * @param GMP|string|int $v
     * @return GMP|string|int
     */
    private function twist($m, $u, $v)
    {
        // this brain explosion:
        // #define twist(m,u,v)  (m ^ (mixBits(u,v) >> 1) ^ ((uint32_t)(-(int32_t)(loBit(v))) & 0x9908b0dfU))
        // #define twist_php(m,u,v)  (m ^ (mixBits(u,v) >> 1) ^ ((uint32_t)(-(int32_t)(loBit(u))) & 0x9908b0dfU))

        $mixBits = self::$math->shiftRight($u & self::$HI_BIT | $v & self::$LO_BITS, 1);

        if ($this->mode === MT_RAND_MT19937) {
            $twist = self::$math->toInt($v & self::$LO_BIT) ? self::$TWIST_CONST : self::$ZERO;
        } else {
            $twist = self::$math->toInt($u & self::$LO_BIT) ? self::$TWIST_CONST : self::$ZERO;
        }

        return $m ^ $mixBits ^ $twist;
    }

    public function generate(): string
    {
        if ($this->stateCount >= self::N) {
            $this->reload();
        }

        $s1 = $this->state[$this->stateCount++];
        $s1 ^= self::$math->shiftRight($s1, 11);
        $s1 ^= self::$math->shiftLeft($s1, 7) & self::$GEN1;
        $s1 ^= self::$math->shiftLeft($s1, 15) & self::$GEN2;
        $s1 ^= self::$math->shiftRight($s1, 18);

        return self::$math->toBinary($s1);
    }

    /**
     * @return array
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function getStates(): array
    {
        $states = array_map(function ($state) {
            return bin2hex(self::$math->toBinary($state));
        }, $this->state);
        $states[] = $this->stateCount;
        $states[] = $this->mode;

        return $states;
    }

    /**
     * @throws Exception
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function loadStates(array $states): bool
    {
        /** @var GMP[] $state */
        $state = array_fill(0, self::N, null);

        for ($i = 0; $i < self::N; $i++) {
            if (!isset($states[$i])) {
                return false;
            }
            $stateBin = @hex2bin($states[$i]);
            if ($stateBin === false) {
                return false;
            }
            $state[$i] = self::$math->fromBinary($stateBin);
        }

        $this->state = $state;
        $count = $states[self::N];
        $mode = $states[self::N + 1];

        if ($mode !== MT_RAND_PHP && $mode !== MT_RAND_MT19937) {
            return false;
        }

        if ($count > self::N) {
            return false;
        }

        $this->stateCount = $count;
        $this->mode = $mode;

        return true;
    }
}
