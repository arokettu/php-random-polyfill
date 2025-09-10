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

use Arokettu\Random\Tests\DevEngines\FromPHP\ByteEngine;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_64.phpt
 */
final class GetIntExpansion64Test extends TestCase
{
    public function testGetInt(): void
    {
        // only when running 64 bit
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped("It's a 64 bit test");
        }

        $randomizer = new Randomizer(new ByteEngine());

        self::assertEquals('0102030405060700', \bin2hex(\pack('P', $randomizer->getInt(0, 0x00FFFFFFFFFFFFFF))));
    }
}
