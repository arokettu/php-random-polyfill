<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer\Methods;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestGetBytesEngine;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/get_bytes.phpt
 */
class GetBytesExpansionTest extends TestCase
{
    public function testGetBytes(): void
    {
        $randomizer = new Randomizer(new TestGetBytesEngine());

        self::assertEquals('Hello', $randomizer->getBytes(5));
        // Returned values are truncated to 64-bits for technical reasons, thus dropping i-z
        self::assertEquals('abcdefghABC', $randomizer->getBytes(11));
        self::assertEquals('success', $randomizer->getBytes(7));
    }
}
