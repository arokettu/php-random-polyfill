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

use Arokettu\Random\Tests\DevEngines\FromPHP\TestShaEngine;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/pickArrayKeys.phpt
 */
final class PickArrayKeysTest extends TestCase
{
    public function testPickArrayKeys(): void
    {
        $engines = [];
        $engines[] = new Mt19937(null, \MT_RAND_MT19937);
        $engines[] = new Mt19937(null, \MT_RAND_PHP);
        $engines[] = new PcgOneseq128XslRr64();
        $engines[] = new Xoshiro256StarStar();
        $engines[] = new Secure();
        $engines[] = new TestShaEngine();

        $array1 = []; // list
        $array2 = []; // associative array with only strings
        $array3 = []; // mixed key array
        for ($i = 0; $i < 500; $i++) {
            $string = \sha1((string)$i);

            $array1[] = $i;
            $array2[$string] = $i;
            $array3[$string] = $i;
            $array3[$i] = $string;
        }

        foreach ($engines as $engine) {
            $randomizer = new Randomizer($engine);

            for ($i = 1; $i < 100; $i++) {
                $result = @$randomizer->pickArrayKeys($array1, $i);

                self::assertEquals($result, \array_unique($result)); // no duplicates
                self::assertEmpty(\array_diff($result, \array_keys($array1))); // no non-keys returned

                $result = @$randomizer->pickArrayKeys($array2, $i);

                self::assertEquals($result, \array_unique($result)); // no duplicates
                self::assertEmpty(\array_diff($result, \array_keys($array2))); // no non-keys returned

                $result = @$randomizer->pickArrayKeys($array3, $i);

                self::assertEquals($result, \array_unique($result)); // no duplicates
                self::assertEmpty(\array_diff($result, \array_keys($array3))); // no non-keys returned
            }
        }
    }
}
