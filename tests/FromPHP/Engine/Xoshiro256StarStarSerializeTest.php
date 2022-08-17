<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/xoshiro256starstar_serialize.phpt
 */
class Xoshiro256StarStarSerializeTest extends TestCase
{
    public function testSerialization(): void
    {
        if (PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        $s = 'O:32:"Random\Engine\Xoshiro256StarStar":2:{i:0;a:0:{}i:1;a:4:{i:0;s:16:"db1c182f1bf60cbb";i:1;s:16:' .
            '"2465f04d36a1c797";i:2;s:16:"da25c09be4fabe33";i:3;s:16:"33a0d052f241624e";}}';

        self::assertEquals($s, serialize(new Xoshiro256StarStar(1234)));
    }
}
