<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\RandomException;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/compatibility_user.phpt
 */
class CompatibilityUserTest extends TestCase
{
    public function testCompatibility(): void
    {
        $native_randomizer = new Randomizer(new Mt19937(1234));
        $user_randomizer = new Randomizer(new class () implements Engine {
            /** @var Engine */
            private $engine;

            public function __construct()
            {
                $this->engine = new Mt19937(1234);
            }

            public function generate(): string
            {
                return $this->engine->generate();
            }
        });
        for ($i = 0; $i < 1000; $i++) {
            $native = $native_randomizer->nextInt();
            $user = $user_randomizer->nextInt();
            self::assertEquals($native, $user);
        }

        try {
            $native_randomizer = new Randomizer(new PcgOneseq128XslRr64(1234));
            $user_randomizer = new Randomizer(new class () implements Engine {
                /** @var Engine */
                private $engine;

                public function __construct()
                {
                    $this->engine = new PcgOneseq128XslRr64(1234);
                }

                public function generate(): string
                {
                    return $this->engine->generate();
                }
            });

            for ($i = 0; $i < 1000; $i++) {
                $native = $native_randomizer->nextInt();
                $user = $user_randomizer->nextInt();
                self::assertEquals($native, $user);
            }
        } catch (RandomException $e) {
            if ($e->getMessage() !== 'Generated value exceeds size of int') {
                throw $e;
            }
        }

        try {
            $native_randomizer = new Randomizer(new Xoshiro256StarStar(1234));
            $user_randomizer = new Randomizer(new class () implements Engine {
                /** @var Engine */
                private $engine;

                public function __construct()
                {
                    $this->engine = new Xoshiro256StarStar(1234);
                }

                public function generate(): string
                {
                    return $this->engine->generate();
                }
            });

            for ($i = 0; $i < 1000; $i++) {
                $native = $native_randomizer->nextInt();
                $user = $user_randomizer->nextInt();
                self::assertEquals($native, $user);
            }
        } catch (RandomException $e) {
            if ($e->getMessage() !== 'Generated value exceeds size of int') {
                throw $e;
            }
        }
    }
}
