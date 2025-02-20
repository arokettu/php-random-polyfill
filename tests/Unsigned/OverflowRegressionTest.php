<?php

declare(strict_types=1);

namespace Arokettu\Unsigned\Tests;

use PHPUnit\Framework\TestCase;

use function Arokettu\Unsigned\from_int;
use function Arokettu\Unsigned\mul;
use function Arokettu\Unsigned\to_int;

class OverflowRegressionTest extends TestCase
{
    public function testMulGeneric32()
    {
        // error on 32 bit platforms

        $value1 = from_int(1812433253, 4);
        $value2 = from_int(1262528769, 4);

        $mul = mul($value1, $value2);

        self::assertEquals(1442526821, to_int($mul));
    }
}
