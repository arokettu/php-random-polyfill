<?php

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
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getBytes.phpt
 */
class GetBytesTest extends TestCase
{
    public function testGetBytes(): void
    {
        $engines = [];
        $engines[] = new Mt19937(null, \MT_RAND_MT19937);
        $engines[] = new Mt19937(null, \MT_RAND_PHP);
        $engines[] = new PcgOneseq128XslRr64();
        $engines[] = new Xoshiro256StarStar();
        $engines[] = new Secure();
        $engines[] = new TestShaEngine();

        foreach ($engines as $engine) {
            $randomizer = new Randomizer($engine);

            for ($i = 1; $i < 1000; $i++) {
                self::assertEquals($i, \strlen($randomizer->getBytes($i)));
            }
        }
    }
}
