<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes adaptation of C code from the PHP Interpreter
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 * @see https://github.com/php/php-src/blob/master/ext/random/engine_secure.c
 */

declare(strict_types=1);

namespace Random\Engine;

use Arokettu\Random\NoDynamicProperties;
use Error;
use Exception;
use Random\CryptoSafeEngine;
use Random\RandomException;

final class Secure implements CryptoSafeEngine
{
    use NoDynamicProperties;

    /**
     * @throws RandomException
     */
    public function generate(): string
    {
        try {
            return \random_bytes(\PHP_INT_SIZE);
            // @codeCoverageIgnoreStart
            // catch unreproducible
        } catch (\Exception $e) {
            // random_bytes throws Exception in <= 8.1 but RandomException in >= 8.2
            throw new RandomException($e->getMessage(), (int)$e->getCode(), $e->getPrevious());
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @throws RandomException
     */
    private function range(int $min, int $max): ?int
    {
        try {
            return \random_int($min, $max);
            // @codeCoverageIgnoreStart
            // catch unreproducible
        } catch (\Exception $e) {
            // random_bytes throws Exception in <= 8.1 but RandomException in >= 8.2
            throw new RandomException($e->getMessage(), (int)$e->getCode(), $e->getPrevious());
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @throws Exception
     */
    public function __sleep(): array
    {
        throw new Exception("Serialization of 'Random\Engine\Secure' is not allowed");
    }

    public function __clone()
    {
        throw new Error('Trying to clone an uncloneable object of class Random\Engine\Secure');
    }
}
