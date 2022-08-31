<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use Arokettu\Random\Tests\DevEngines\Xoshiro128PP;
use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/user_xoshiro128plusplus.phpt
 */
class UserXoshiro128PlusPlusTest extends TestCase
{
    public function testXoshiro(): void
    {
        // only when running 64 bit
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped("It's a 64 bit test");
        }

        $g = Xoshiro128PP::fromNumbers(1, 2, 3, 4);

        for ($i = 0; $i < 102400; $i++) {
            $g->generate();
        }

        self::assertEquals('fa3c872c', \bin2hex($g->generate()));
    }
}
