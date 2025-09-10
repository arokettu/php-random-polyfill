<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes test code from the PHP Interpreter
 * @copyright 1999-2012 The PHP Group. All rights reserved.
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_32.phpt
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_64.phpt
 */
final class ByteEngine implements Engine
{
    /** @var int */
    public $count = 0;

    public function generate(): string
    {
        return "\x01\x02\x03\x04\x05\x06\x07\x08"[$this->count++];
    }
}
