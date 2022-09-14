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
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/shuffleArray.phpt
 */
class ShuffleArrayTest extends TestCase
{
    public function testShuffleArray(): void
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

            // This test is slow, test all numbers smaller than 50 and then in steps of 677 (which is prime).
            for ($i = 1; $i < 5000; $i += ($i < 50 ? 1 : 677)) {
                $array = \range(1, $i);

                $result = $randomizer->shuffleArray($array);

                \sort($result);

                self::assertEquals($array, $result); // is a permutation
            }
        }
    }
}
