<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer\Methods;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestByteEngine;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_64.phpt
 */
class GetIntExpansion64 extends TestCase
{
    public function testGetInt(): void
    {
        // only when running 64 bit
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped("It's a 64 bit test");
        }

        $randomizer = new Randomizer(new TestByteEngine());

        self::assertEquals('0102030405060700', \bin2hex(\pack('V', $randomizer->getInt(0, 0x00FFFFFFFFFFFFFF))));
    }
}
