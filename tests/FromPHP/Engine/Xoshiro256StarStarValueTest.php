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

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/xoshiro256starstar_value.phpt
 */
final class Xoshiro256StarStarValueTest extends TestCase
{
    public function testValue(): void
    {
        $engine = new Xoshiro256StarStar(1234);

        for ($i = 0; $i < 10000; $i++) {
            $engine->generate();
        }

        $engine->jump();
        $engine->jumpLong();

        self::assertEquals('1f197e9ca7969123', \bin2hex($engine->generate()));
    }
}
