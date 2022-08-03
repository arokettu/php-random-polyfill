<?php

declare(strict_types=1);

namespace Random\Engine;

use Exception;
use GMP;
use Random\Engine;
use RuntimeException;
use TypeError;
use ValueError;

use function bin2hex;
use function get_debug_type;
use function gmp_export;
use function gmp_import;
use function gmp_init;
use function is_int;
use function is_string;
use function random_bytes;
use function str_split;
use function strlen;

use const GMP_LITTLE_ENDIAN;
use const GMP_LSW_FIRST;

final class Xoshiro256StarStar implements Engine
{
    use Shared\Serialization;

    private const SIZEOF_UINT64_T = 8;

    /** @var GMP[] */
    private $state;

    /** @var GMP|null 64-bit bitmask */
    private static $UINT64_MASK = null;
    /** @var GMP|null */
    private static $SPLITMIX64_1;
    /** @var GMP|null */
    private static $SPLITMIX64_2;
    /** @var GMP|null */
    private static $SPLITMIX64_3;

    /**
     * @param string|int|null $seed
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function __construct($seed = null)
    {
        $this->initConst();

        if ($seed === null) {
            try {
                $this->seedString(random_bytes(32));
            } catch (Exception $e) {
                throw new RuntimeException('Random number generation failed');
            }
            return;
        }

        if (is_string($seed)) {
            if (strlen($seed) !== 32) {
                throw new ValueError('state strings must be 32 bytes');
            }

            $this->seedString($seed);
            return;
        }

        /** @psalm-suppress RedundantConditionGivenDocblockType we don't trust user input */
        if (is_int($seed)) {
            $this->seedInt($seed);
            return;
        }

        throw new TypeError(
            __METHOD__ .
            '(): Argument #1 ($seed) must be of type string|int|null, ' .
            get_debug_type($seed) . ' given'
        );
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function initConst(): void
    {
        if (self::$UINT64_MASK === null) {
            self::$UINT64_MASK = gmp_init('ffffffffffffffff', 16);
        }
        if (self::$SPLITMIX64_1 === null) {
            self::$SPLITMIX64_1 = gmp_init('9e3779b97f4a7c15', 16);
        }
        if (self::$SPLITMIX64_2 === null) {
            self::$SPLITMIX64_2 = gmp_init('bf58476d1ce4e5b9', 16);
        }
        if (self::$SPLITMIX64_3 === null) {
            self::$SPLITMIX64_3 = gmp_init('94d049bb133111eb', 16);
        }
    }

    private function seedInt(int $seed): void
    {
        $seed = $seed & self::$UINT64_MASK;

        $this->seed256(
            $this->splitmix64($seed),
            $this->splitmix64($seed),
            $this->splitmix64($seed),
            $this->splitmix64($seed)
        );
    }

    private function splitmix64(GMP &$seed): GMP
    {
        $r = $seed = ($seed + self::$SPLITMIX64_1) & self::$UINT64_MASK;
        $r = (($r ^ ($r >> 30)) * self::$SPLITMIX64_2) & self::$UINT64_MASK;
        $r = (($r ^ ($r >> 27)) * self::$SPLITMIX64_3) & self::$UINT64_MASK;
        return ($r ^ ($r >> 31));
    }

    private function seedString(string $seed): void
    {
        $seeds = str_split($seed, 8);

        $this->seed256(
            gmp_import($seeds[0], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST),
            gmp_import($seeds[1], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST),
            gmp_import($seeds[2], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST),
            gmp_import($seeds[3], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST)
        );
    }

    private function seed256(GMP $s0, GMP $s1, GMP $s2, GMP $s3): void
    {
        $this->state = [$s0, $s1, $s2, $s3];
    }

    public function generate(): string
    {
        return "\0";
    }

    public function jump(): void
    {

    }

    public function jumpLong(): void
    {

    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function getStates(): array
    {
        return [
            bin2hex(gmp_export($this->state[0], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST)),
            bin2hex(gmp_export($this->state[1], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST)),
            bin2hex(gmp_export($this->state[2], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST)),
            bin2hex(gmp_export($this->state[3], self::SIZEOF_UINT64_T, GMP_LITTLE_ENDIAN | GMP_LSW_FIRST)),
        ];
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function loadStates(array $states): void
    {
        // TODO: Implement loadStates() method.
    }
}
