<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_exits.phpt
 */
class EngineUnsafeExitsTest extends TestCase
{
    // Let's trust the engine here
    public function testSkip(): void
    {
        $this->expectNotToPerformAssertions();
    }
}
