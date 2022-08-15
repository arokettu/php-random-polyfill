<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/user_compatibility.phpt
 */
class UserCompatibilityTest extends TestCase
{
    public function testCompatibilityMt(): void
    {
        $nativeEngine = new Mt19937(1234);
        $userEngine = new class () implements Engine {
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
        };

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($nativeEngine->generate(), $userEngine->generate());
        }
    }

    public function testCompatibilityPcg(): void
    {
        $nativeEngine = new PcgOneseq128XslRr64(1234);
        $userEngine = new class () implements Engine {
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
        };

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($nativeEngine->generate(), $userEngine->generate());
        }
    }

    public function testCompatibilityXoshiro(): void
    {
        $nativeEngine = new Xoshiro256StarStar(1234);
        $userEngine = new class () implements Engine {
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
        };

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($nativeEngine->generate(), $userEngine->generate());
        }
    }
}
