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

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Error;
use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/readonly.phpt
 */
final class ReadonlyTest extends TestCase
{
    public function testReadonly(): void
    {
        $randomizer = new Randomizer(new PcgOneseq128XslRr64(1234));
        $referenceRandomizer = new Randomizer(new PcgOneseq128XslRr64(1234));

        try {
            $randomizer->engine = new Xoshiro256StarStar(1234);
        } catch (Throwable $e) {
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            for ($i = 0; $i < 10000; $i++) {
                self::assertEquals($referenceRandomizer->getInt(0, $i), $randomizer->getInt(0, $i));
            }

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
