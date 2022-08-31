<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes adaptation of C code from the PHP Interpreter
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 * @see https://github.com/php/php-src/blob/master/ext/random/engine_pcgoneseq128xslrr64.c
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
use TypeError;
use ValueError;

final class PcgOneseq128XslRr64 implements Engine, Serializable
{
    use NoDynamicProperties;
    use Serialization;

    /** @var Math */
    private static $math64;
    /** @var Math */
    private static $math128;

    /** @var GMP|string|int */
    private static $STEP_MUL_CONST;
    /** @var GMP|string|int */
    private static $STEP_ADD_CONST;
    /** @var GMP|string|int */
    private static $ZERO;
    /** @var GMP|string|int */
    private static $ONE;

    /**
     * @var GMP|string|int state
     * @psalm-suppress PropertyNotSetInConstructor Psalm doesn't traverse several levels apparently
     */
    private $state;

    /**
     * @param string|int|null $seed
     */
    public function __construct($seed = null)
    {
        $this->initMath();

        if (\is_int($seed)) {
            $this->seedInt($seed);
            return;
        }

        if ($seed === null) {
            try {
                $seed = \random_bytes(Math::SIZEOF_UINT128_T);
            } catch (Exception $e) {
                throw new RuntimeException('Failed to generate a random seed');
            }
        }

        /** @psalm-suppress RedundantConditionGivenDocblockType we don't trust user input */
        if (\is_string($seed)) {
            if (\strlen($seed) !== Math::SIZEOF_UINT128_T) {
                throw new ValueError(__METHOD__ . '(): Argument #1 ($seed) must be a 16 byte (128 bit) string');
            }

            $this->seedString($seed);
            return;
        }

        throw new TypeError(
            __METHOD__ .
            '(): Argument #1 ($seed) must be of type string|int|null, ' .
            \get_debug_type($seed) . ' given'
        );
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     * @psalm-suppress DocblockTypeContradiction the "constants" are initialized here
     */
    private function initMath(): void
    {
        if (self::$math64 === null) {
            self::$math64  = Math::create(Math::SIZEOF_UINT64_T);
            self::$math128 = Math::create(Math::SIZEOF_UINT128_T);

            // 2549297995355413924 << 64 | 4865540595714422341
            self::$STEP_MUL_CONST = self::$math128->fromHex('2360ed051fc65da44385df649fccf645');
            // 6364136223846793005 << 64 | 1442695040888963407
            self::$STEP_ADD_CONST = self::$math128->fromHex('5851f42d4c957f2d14057b7ef767814f');

            self::$ZERO = self::$math128->fromInt(0);
            self::$ONE  = self::$math128->fromInt(1);
        }
    }

    private function seedInt(int $seed): void
    {
        // or with 128 bit zero to expand 64 bit to 128
        $this->seed128(self::$math64->fromInt($seed) | self::$ZERO);
    }

    private function seedString(string $seed): void
    {
        [$hi, $lo] = \str_split($seed, Math::SIZEOF_UINT64_T);

        $this->seed128(self::$math128->fromBinary($lo . $hi));
    }

    /**
     * @param GMP|string|int $seed
     */
    private function seed128($seed): void
    {
        $this->state = self::$ZERO;
        $this->step();
        $this->state = self::$math128->add($this->state, $seed);
        $this->step();
    }

    private function step(): void
    {
        $this->state = self::$math128->add(
            self::$math128->mul($this->state, self::$STEP_MUL_CONST),
            self::$STEP_ADD_CONST
        );
    }

    public function generate(): string
    {
        $this->step();
        return $this->rotr64($this->state);
    }

    /**
     * @param GMP|string|int $state
     */
    private function rotr64($state): string
    {
        [$hi, $lo] = self::$math128->splitHiLo($state);

        $v = $hi ^ $lo;
        $s = self::$math64->toInt(self::$math64->shiftRight($hi, 58));

        $result =
            self::$math64->shiftRight($v, $s) |
            self::$math64->shiftLeft($v, -$s & 63);

        return self::$math64->toBinary($result);
    }

    public function jump(int $advance): void
    {
        $curMult = self::$STEP_MUL_CONST;
        $curPlus = self::$STEP_ADD_CONST;
        $accMult = self::$ONE;
        $accPlus = self::$ZERO;

        if ($advance < 0) {
            throw new ValueError(__METHOD__ . '(): Argument #1 ($advance) must be greater than or equal to 0');
        }

        while ($advance > 0) {
            if ($advance & 1) {
                $accMult = self::$math128->mul($accMult, $curMult);
                $accPlus = self::$math128->add(self::$math128->mul($accPlus, $curMult), $curPlus);
            }
            $curPlus = self::$math128->mul(self::$math128->add($curMult, self::$ONE), $curPlus);
            $curMult = self::$math128->mul($curMult, $curMult);

            $advance >>= 1;
        }

        $this->state = self::$math128->add(self::$math128->mul($accMult, $this->state), $accPlus);
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function getStates(): array
    {
        [$lo, $hi] = \str_split(self::$math128->toBinary($this->state), Math::SIZEOF_UINT64_T);
        return [
            \bin2hex($hi),
            \bin2hex($lo),
        ];
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     * @throws Exception
     */
    private function loadStates(array $states): bool
    {
        if (!\array_is_list($states) || \count($states) < 2) {
            return false;
        }
        [$hi, $lo] = $states;
        if (\strlen($hi) !== Math::SIZEOF_UINT64_T * 2 || \strlen($lo) !== Math::SIZEOF_UINT64_T * 2) {
            return false;
        }
        $hiBin = @\hex2bin($hi);
        $loBin = @\hex2bin($lo);
        if ($hiBin === false || $loBin === false) {
            return false;
        }
        $this->state = self::$math128->fromBinary($loBin . $hiBin);

        return true;
    }
}
