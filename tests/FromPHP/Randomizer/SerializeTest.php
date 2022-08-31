<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\SerializeTestUserEngine;
use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/serialize.phpt
 */
class SerializeTest extends TestCase
{
    public function testSerializeSuccess(): void
    {
        $engines = [];
        $engines[] = new Mt19937(\random_int(\PHP_INT_MIN, \PHP_INT_MAX), \MT_RAND_MT19937);
        $engines[] = new Mt19937(\random_int(\PHP_INT_MIN, \PHP_INT_MAX), \MT_RAND_PHP);
        $engines[] = new PcgOneseq128XslRr64(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));
        $engines[] = new Xoshiro256StarStar(\random_int(\PHP_INT_MIN, \PHP_INT_MAX));

        global $___serialize_test_generate;
        $___serialize_test_generate = \random_bytes(16);

        $engines[] = new SerializeTestUserEngine();

        foreach ($engines as $engine) {
            $randomizer = new Randomizer($engine);
            $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX);

            $randomizer2 = \unserialize(@\serialize($randomizer));

            self::assertEquals(
                $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX),
                $randomizer2->getInt(\PHP_INT_MIN, \PHP_INT_MAX)
            );
        }
    }

    public function testSerializeSecure(): void
    {
        $engine = new Secure();

        $randomizer = new Randomizer($engine);
        $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX);

        try {
            $randomizer2 = \unserialize(@\serialize($randomizer));
        } catch (Throwable $e) {
            self::assertEquals(\Exception::class, \get_class($e));
            self::assertEquals(
                "Serialization of 'Random\Engine\Secure' is not allowed",
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSerializeAnon(): void
    {
        global $___serialize_test_generate;
        $___serialize_test_generate = \random_bytes(16);

        $engine = new class () implements Engine {
            public function generate(): string
            {
                /** @psalm-suppress InvalidGlobal */
                global $___serialize_test_generate;
                return $___serialize_test_generate;
            }
        };

        $randomizer = new Randomizer($engine);
        $randomizer->getInt(\PHP_INT_MIN, \PHP_INT_MAX);

        try {
            $randomizer2 = \unserialize(@\serialize($randomizer));
        } catch (Throwable $e) {
            self::assertEquals(\Exception::class, \get_class($e));
            if (\method_exists(__CLASS__, 'assertMatchesRegularExpression')) {
                self::assertMatchesRegularExpression(
                    "/Serialization of '.*@anonymous' is not allowed/",
                    $e->getMessage()
                );
            } else {
                self::assertRegExp(
                    "/Serialization of '.*@anonymous' is not allowed/",
                    $e->getMessage()
                );
            }
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
