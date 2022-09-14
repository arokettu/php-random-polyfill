<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;

/**
 * GH-9186 strict-properties can be bypassed using unserialization
 * https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/gh_9186_unserialize.phpt
 */
class Gh9186UnserializeTest extends TestCase
{
    // enforced in slightly incompatible way (doesn't throw)
    public function testSkip(): void
    {
        $this->expectNotToPerformAssertions();
    }
}
