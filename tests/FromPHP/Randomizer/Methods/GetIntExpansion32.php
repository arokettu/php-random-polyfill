<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer\Methods;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestByteEngine;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_32.phpt
 */
class GetIntExpansion32 extends TestCase
{
    public function testGetInt(): void
    {
        $randomizer = new Randomizer(new TestByteEngine());

        self::assertEquals('01020300', \bin2hex(\pack('V', $randomizer->getInt(0, 0x00FFFFFF))));
    }
}
