<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\BasicTestUserEngine;
use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\RandomException;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/basic.phpt
 */
class BasicTest extends TestCase
{
    // Original test had 1000, but it's too heavy for the non-native lib
    // also, tests are mostly duplicates
    private const ITERATIONS = 100;

    public function testBasic(): void
    {
        $engines = [];
        $engines[] = new Mt19937(\random_int(\PHP_INT_MIN, \PHP_INT_MAX), \MT_RAND_MT19937);
        $engines[] = new Mt19937(\random_int(\PHP_INT_MIN, \PHP_INT_MAX), \MT_RAND_PHP);
        $engines[] = new PcgOneseq128XslRr64(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));
        $engines[] = new Xoshiro256StarStar(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));
        $engines[] = new Secure();
        $engines[] = new class () implements Engine {
            public function generate(): string
            {
                return \random_bytes(16);
            }
        };
        $engines[] = new BasicTestUserEngine();

        foreach ($engines as $engine) {
            $randomizer = new Randomizer($engine);

            // nextInt
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                try {
                    $randomizer->nextInt();
                } catch (RandomException $e) {
                    self::assertEquals('Generated value exceeds size of int', $e->getMessage(), \get_class($engine));
                }
            }

            // getInt
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                $result = $randomizer->getInt(-50, 50);
                self::assertGreaterThanOrEqual(-50, $result, \get_class($engine));
                self::assertLessThanOrEqual(50, $result, \get_class($engine));
            }

            // getBytes
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                $length = \random_int(1, 1024);
                self::assertEquals($length, \strlen($randomizer->getBytes($length)), \get_class($engine));
            }

            // shuffleArray
            $array = \range(1, self::ITERATIONS);
            $shuffled_array = $randomizer->shuffleArray($array);
            self::assertNotEquals($array, $shuffled_array, \get_class($engine));

            // shuffleBytes
            $string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ' .
                'ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco ' .
                'laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in ' .
                'voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non ' .
                'proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
            $shuffled_string = $randomizer->shuffleBytes($string);
            self::assertNotEquals($string, $shuffled_string, \get_class($engine));
        }
    }
}
