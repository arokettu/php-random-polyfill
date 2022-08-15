<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/mt19937_value.phpt
 */
class Mt19937ValueTest extends TestCase
{
    public function testValue(): void
    {
        $engine = new Mt19937(1234);

        for ($i = 0; $i < 10000; $i++) {
            $engine->generate();
        }

        self::assertEquals('60fe95d9', \bin2hex($engine->generate()));
    }
}
