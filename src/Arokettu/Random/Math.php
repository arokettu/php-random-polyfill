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
    public const SIZEOF_64 = 8;

    /** @var Math[] */
    private static $maths = [];

    public static function create(int $sizeof): self
    {
        return self::$maths[$sizeof] ?? self::$maths[$sizeof] = self::build($sizeof);
    }

    protected static function build(int $sizeof): self
    {
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
     * @param string|GMP $value
     * @param int $shift
     * @return string|GMP
     */
    abstract public function shiftLeft($value, int $shift);

    /**
     * @param string|GMP $value
     * @param int $shift
     * @return string|GMP
     */
    abstract public function shiftRight($value, int $shift);

    /**
     * @param string|GMP $value1
     * @param string|GMP $value2
     * @return string|GMP
     */
    abstract public function add($value1, $value2);

    /**
     * @param string|GMP $value1
     * @param string|GMP $value2
     * @return string|GMP
     */
    abstract public function mul($value1, $value2);

    /**
     * @param string $value
     * @return string|GMP
     */
    abstract public function fromHex(string $value);

    /**
     * @param int $value
     * @return string|GMP
     */
    abstract public function fromInt(int $value);

    /**
     * @param string $value
     * @return string|GMP
     */
    abstract public function fromBinary(string $value);

    /**
     * @param string|GMP $value
     */
    abstract public function toBinary($value): string;
}
