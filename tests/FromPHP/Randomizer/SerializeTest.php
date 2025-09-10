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

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestShaEngine;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/serialize.phpt
 */
final class SerializeTest extends TestCase
{
    public function testSerializeSuccess(): void
    {
        $engines = [];
        $engines[] = new Mt19937(1234, \MT_RAND_MT19937);
        $engines[] = new Mt19937(1234, \MT_RAND_PHP);
        $engines[] = new PcgOneseq128XslRr64(1234);
        $engines[] = new Xoshiro256StarStar(1234);
        $engines[] = new TestShaEngine("1234");

        foreach ($engines as $engine) {
            $randomizer = new Randomizer($engine);

            for ($i = 0; $i < 10000; $i++) {
                $randomizer->getInt(0, $i);
            }

            $randomizer2 = \unserialize(@\serialize($randomizer));

            for ($i = 0; $i < 10000; $i++) {
                self::assertEquals(
                    $randomizer->getInt(0, $i),
                    $randomizer2->getInt(0, $i)
                );
            }
        }
    }
}
