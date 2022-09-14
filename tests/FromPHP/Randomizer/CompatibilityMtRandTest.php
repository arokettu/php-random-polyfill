<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/compatibility_mt_rand.phpt
 */
class CompatibilityMtRandTest extends TestCase
{
    public function testCompatibility(): void
    {
        $randomizer = new Randomizer(new Mt19937(1234, \MT_RAND_PHP));
        \mt_srand(1234, \MT_RAND_PHP);

        for ($i = 0; $i < 10000; $i++) {
            self::assertEquals(\mt_rand(), $randomizer->nextInt());
        }

        for ($i = 0; $i < 10000; $i++) {
            self::assertEquals(\mt_rand(0, $i), $randomizer->getInt(0, $i));
        }

        $randomizer = new Randomizer(new Mt19937(1234, \MT_RAND_MT19937));
        \mt_srand(1234, \MT_RAND_MT19937);

        for ($i = 0; $i < 10000; $i++) {
            self::assertEquals(\mt_rand(), $randomizer->nextInt());
        }

        for ($i = 0; $i < 10000; $i++) {
            self::assertEquals(\mt_rand(0, $i), $randomizer->getInt(0, $i));
        }
    }
}
