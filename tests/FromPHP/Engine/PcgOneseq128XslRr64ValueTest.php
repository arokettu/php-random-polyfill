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

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/pcgoneseq128xslrr64_value.phpt
 */
class PcgOneseq128XslRr64ValueTest extends TestCase
{
    public function testValue(): void
    {
        $engine = new PcgOneseq128XslRr64(1234);

        for ($i = 0; $i < 10000; $i++) {
            $engine->generate();
        }

        $engine->jump(1234567);

        self::assertEquals('b88e2a0f23720a1d', \bin2hex($engine->generate()));
    }
}
