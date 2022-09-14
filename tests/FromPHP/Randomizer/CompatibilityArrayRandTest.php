<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/compatibility_array_rand.phpt
 */
class CompatibilityArrayRandTest extends TestCase
{
    // known incompatibility, skip
    public function testSkip(): void
    {
        $this->expectNotToPerformAssertions();
    }
}
