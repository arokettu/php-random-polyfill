<?php

declare(strict_types=1);

namespace Random\Engine;

use Arokettu\Random\BigIntExportImport;
use Arokettu\Random\Serialization;
use Exception;
use GMP;
use Random\Engine;
use RuntimeException;
use Serializable;
use TypeError;
use ValueError;

use function bin2hex;
use function get_debug_type;
use function gmp_init;
use function is_int;
use function is_string;
use function random_bytes;
use function str_split;
use function strlen;

final class Xoshiro256StarStar implements Engine, Serializable
{
    use BigIntExportImport;
    use Serialization;

    /**
     * @var GMP[]
     * @psalm-suppress PropertyNotSetInConstructor Psalm doesn't traverse several levels apparently
     */
    private $state;

    /** @var GMP|null */
    private static $SPLITMIX64_1;
    /** @var GMP|null */
    private static $SPLITMIX64_2;
    /** @var GMP|null */
    private static $SPLITMIX64_3;
    /** @var GMP|null */
    private static $JUMP1;
    /** @var GMP|null */
    private static $JUMP2;
    /** @var GMP|null */
    private static $JUMP3;
    /** @var GMP|null */
    private static $JUMP4;
    /** @var GMP|null */
    private static $JUMP_LONG1;
    /** @var GMP|null */
    private static $JUMP_LONG2;
    /** @var GMP|null */
    private static $JUMP_LONG3;
    /** @var GMP|null */
    private static $JUMP_LONG4;

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
        $this->initGmpConst();

        if (self::$SPLITMIX64_1 === null) {
            self::$SPLITMIX64_1 = gmp_init('9e3779b97f4a7c15', 16);
        }
        if (self::$SPLITMIX64_2 === null) {
            self::$SPLITMIX64_2 = gmp_init('bf58476d1ce4e5b9', 16);
        }
        if (self::$SPLITMIX64_3 === null) {
            self::$SPLITMIX64_3 = gmp_init('94d049bb133111eb', 16);
        }
        if (self::$JUMP1 === null) {
            self::$JUMP1 = gmp_init('180ec6d33cfd0aba', 16);
        }
        if (self::$JUMP2 === null) {
            self::$JUMP2 = gmp_init('d5a61266f0c9392c', 16);
        }
        if (self::$JUMP3 === null) {
            self::$JUMP3 = gmp_init('a9582618e03fc9aa', 16);
        }
        if (self::$JUMP4 === null) {
            self::$JUMP4 = gmp_init('39abdc4529b1661c', 16);
        }
        if (self::$JUMP_LONG1 === null) {
            self::$JUMP_LONG1 = gmp_init('76e15d3efefdcbbf', 16);
        }
        if (self::$JUMP_LONG2 === null) {
            self::$JUMP_LONG2 = gmp_init('c5004e441c522fb3', 16);
        }
        if (self::$JUMP_LONG3 === null) {
            self::$JUMP_LONG3 = gmp_init('77710069854ee241', 16);
        }
        if (self::$JUMP_LONG4 === null) {
            self::$JUMP_LONG4 = gmp_init('39109bb02acbe635', 16);
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
            $this->importGmp64($seeds[0]),
            $this->importGmp64($seeds[1]),
            $this->importGmp64($seeds[2]),
            $this->importGmp64($seeds[3])
        );
    }

    private function seed256(GMP $s0, GMP $s1, GMP $s2, GMP $s3): void
    {
        $this->state = [$s0, $s1, $s2, $s3];
    }

    public function generate(): string
    {
        $r = ($this->rotl($this->state[1] * 5, 7) * 9) & self::$UINT64_MASK;
        $t = ($this->state[1]) << 17 & self::$UINT64_MASK;

        $this->state[2] ^= $this->state[0];
        $this->state[3] ^= $this->state[1];
        $this->state[1] ^= $this->state[2];
        $this->state[0] ^= $this->state[3];

        $this->state[2] ^= $t;

        $this->state[3] = $this->rotl($this->state[3], 45);

        return $this->exportGmp64($r);
    }

    private function rotl(GMP $x, int $k): GMP
    {
        $x = $x & self::$UINT64_MASK;
        return (($x << $k) | ($x >> (64 - $k))) & self::$UINT64_MASK;
    }

    public function jump(): void
    {
        $this->doJump([
            self::$JUMP1,
            self::$JUMP2,
            self::$JUMP3,
            self::$JUMP4,
        ]);
    }

    public function jumpLong(): void
    {
        $this->doJump([
            self::$JUMP_LONG1,
            self::$JUMP_LONG2,
            self::$JUMP_LONG3,
            self::$JUMP_LONG4,
        ]);
    }

    private function doJump(array $jmp): void
    {
        $s0 = $s1 = $s2 = $s3 = gmp_init(0);
        $one = gmp_init(1);

        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 64; $j++) {
                if (($jmp[$i] & $one << $j) > 0) {
                    $s0 ^= $this->state[0];
                    $s1 ^= $this->state[1];
                    $s2 ^= $this->state[2];
                    $s3 ^= $this->state[3];
                }

                $this->generate();
            }
        }

        $this->state[0] = $s0;
        $this->state[1] = $s1;
        $this->state[2] = $s2;
        $this->state[3] = $s3;
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function getStates(): array
    {
        return [
            bin2hex($this->exportGmp64($this->state[0])),
            bin2hex($this->exportGmp64($this->state[1])),
            bin2hex($this->exportGmp64($this->state[2])),
            bin2hex($this->exportGmp64($this->state[3])),
        ];
    }

    /**
     * @psalm-suppress TraitMethodSignatureMismatch abstract private is 8.0+
     */
    private function loadStates(array $states): void
    {
        $this->state = [];
        for ($i = 0; $i < 4; $i++) {
            $this->state[$i] = $this->importGmp64(hex2bin($states[$i]));
        }
    }
}
