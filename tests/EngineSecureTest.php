<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Secure;
use Random\Randomizer;

class EngineSecureTest extends TestCase
{
    public function testWorks(): void
    {
        $engine = new Secure();
        self::assertNotEmpty($engine->generate());
        $rnd = new Randomizer($engine);
        $int = $rnd->getInt(1, 1000);
        self::assertGreaterThanOrEqual(1, $int);
        self::assertLessThanOrEqual(1000, $int);
    }

    public function testNonSerializable(): void
    {
        $this->expectException(\Exception::class);
        serialize(new Secure());
    }
}
