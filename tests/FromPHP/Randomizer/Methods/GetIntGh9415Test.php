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

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * GH-9415: Randomizer::getInt(0, 2**32 - 1) with Mt19937 always returns 1
 * polyfill never was affected but import it anyway
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_gh9415.phpt
 */
final class GetIntGh9415Test extends TestCase
{
    public function testGh9415(): void
    {
        $randomizer = new Randomizer(new Mt19937(1234));
        // Parameters shifted by -2147483648 to be compatible with 32-bit.
        self::assertEquals(-1324913873, $randomizer->getInt(-2147483647 - 1, 2147483647));

        $randomizer = new Randomizer(new Mt19937(4321));
        self::assertEquals(-1843387587, $randomizer->getInt(-2147483647 - 1, 2147483647));
    }
}
