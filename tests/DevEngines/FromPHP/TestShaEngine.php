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
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/engines.inc
 */
class TestShaEngine implements Engine
{
    /** @var string */
    private $state;

    public function __construct(?string $state = null)
    {
        if ($state !== null) {
            $this->state = $state;
        } else {
            $this->state = \random_bytes(20);
        }
    }

    public function generate(): string
    {
        $this->state = \sha1($this->state, true);

        return \substr($this->state, 0, 8);
    }
}
