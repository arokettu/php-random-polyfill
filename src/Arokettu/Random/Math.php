<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Arokettu\Random;

use GMP;

use function extension_loaded;

abstract class Math
{
    public const SIZEOF_UINT32_T = 4;
    public const SIZEOF_UINT64_T = 8;
    public const SIZEOF_UINT128_T = 16;

    /** @var Math[] */
    private static $maths = [];

    public static function create(int $sizeof): self
    {
        return self::$maths[$sizeof] ?? self::$maths[$sizeof] = self::build($sizeof);
    }

    protected static function build(int $sizeof): self
    {
        // only less because PHP int is always signed
        if ($sizeof < PHP_INT_SIZE) {
            return new MathNative($sizeof);
        }

        if (extension_loaded('gmp')) {
            return new MathGMP($sizeof);
        }

        return new MathUnsigned($sizeof);
    }

    /**
     * @param int $sizeof
     */
    abstract protected function __construct(int $sizeof);

    /**
     * @param int|string|GMP $value
     * @param int $shift
     * @return int|string|GMP
     */
    abstract public function shiftLeft($value, int $shift);

    /**
     * @param int|string|GMP $value
     * @param int $shift
     * @return int|string|GMP
     */
    abstract public function shiftRight($value, int $shift);

    /**
     * @param int|string|GMP $value1
     * @param int|string|GMP $value2
     * @return int|string|GMP
     */
    abstract public function add($value1, $value2);

    /**
     * @param int|string|GMP $value1
     * @param int $value2
     * @return int|string|GMP
     */
    abstract public function addInt($value1, int $value2);

    /**
     * @param int|string|GMP $value1
     * @param int|string|GMP $value2
     * @return int|string|GMP
     */
    abstract public function sub($value1, $value2);

    /**
     * @param int|string|GMP $value1
     * @param int $value2
     * @return int|string|GMP
     */
    abstract public function subInt($value1, int $value2);

    /**
     * @param int|string|GMP $value1
     * @param int|string|GMP $value2
     * @return int|string|GMP
     */
    abstract public function mul($value1, $value2);

    /**
     * @param int|string|GMP $value1
     * @param int|string|GMP $value2
     * @return int|string|GMP
     */
    abstract public function mod($value1, $value2);

    /**
     * @param int|string|GMP $value1
     * @param int|string|GMP $value2
     * @return int
     */
    abstract public function compare($value1, $value2): int;

    /**
     * @param string $value
     * @return int|string|GMP
     */
    abstract public function fromHex(string $value);

    /**
     * @param int $value
     * @return int|string|GMP
     */
    abstract public function fromInt(int $value);

    /**
     * @param string $value
     * @return int|string|GMP
     */
    abstract public function fromBinary(string $value);

    /**
     * @param int|string|GMP $value
     */
    abstract public function toInt($value): int;

    /**
     * @param int|string|GMP $value
     */
    abstract public function toSignedInt($value): int;

    /**
     * @param int|string|GMP $value
     */
    abstract public function toBinary($value): string;

    /**
     * @param int|string|GMP $value
     * @return int[]|string[]|GMP[]
     */
    abstract public function splitHiLo($value): array;
}
