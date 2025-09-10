<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes test code from the PHP Interpreter
 * @copyright 1999-2022 The PHP Group. All rights reserved.
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer\Methods;

use Arokettu\Random\Tests\DevEngines\FromPHP\GetBytesExpansionEngine;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/get_bytes.phpt
 */
final class GetBytesExpansionTest extends TestCase
{
    public function testGetBytes(): void
    {
        $randomizer = new Randomizer(new GetBytesExpansionEngine());

        self::assertEquals('Hello', $randomizer->getBytes(5));
        // Returned values are truncated to 64-bits for technical reasons, thus dropping i-z
        self::assertEquals('abcdefghABC', $randomizer->getBytes(11));
        self::assertEquals('success', $randomizer->getBytes(7));
    }
}
