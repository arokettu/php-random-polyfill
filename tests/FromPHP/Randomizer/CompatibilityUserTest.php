<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestWrapperEngine;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/compatibility_user.phpt
 */
class CompatibilityUserTest extends TestCase
{
    public function testCompatibility(): void
    {
        $engines = [];
        $engines[] = new Mt19937(1234);
        $engines[] = new PcgOneseq128XslRr64(1234);
        $engines[] = new Xoshiro256StarStar(1234);

        foreach ($engines as $engine) {
            $native_randomizer = new Randomizer(clone $engine);
            $user_randomizer = new Randomizer(new TestWrapperEngine(clone $engine));

            for ($i = 0; $i < 10000; $i++) {
                $native = $native_randomizer->getInt(0, $i);
                $user = $user_randomizer->getInt(0, $i);

                self::assertEquals($native, $user);
            }
        }
    }
}
