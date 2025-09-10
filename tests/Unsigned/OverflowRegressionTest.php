<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\Unsigned;

use PHPUnit\Framework\TestCase;

use function Arokettu\Random\Unsigned\from_int;
use function Arokettu\Random\Unsigned\mul;
use function Arokettu\Random\Unsigned\to_int;

class OverflowRegressionTest extends TestCase
{
    public function testMulGeneric32(): void
    {
        // error on 32 bit platforms

        $value1 = from_int(1812433253, 4);
        $value2 = from_int(1262528769, 4);

        $mul = mul($value1, $value2);

        self::assertEquals(1442526821, to_int($mul));
    }
}
