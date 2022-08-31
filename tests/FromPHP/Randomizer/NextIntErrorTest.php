<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;
use Random\RandomException;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/nextint_error.phpt
 */
class NextIntErrorTest extends TestCase
{
    public function testNextIntError(): void
    {
        if (\PHP_INT_SIZE > 4) {
            $this->markTestSkipped('32 bit only');
        }

        $this->expectException(RandomException::class);
        $this->expectExceptionMessage('Generated value exceeds size of int');

        $randomizer = new Randomizer(new Xoshiro256StarStar());

        \var_dump($randomizer->nextInt());
    }
}
