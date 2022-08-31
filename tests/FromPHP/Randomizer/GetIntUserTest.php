<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/get_int_user.phpt
 */
class GetIntUserTest extends TestCase
{
    public function testGetInt(): void
    {
        $randomizer = new Randomizer(
            new class () implements Engine
            {
                /** @var int */
                public $count = 0;

                public function generate(): string
                {
                    return "\x01\x02\x03\x04\x05\x06\x07\x08"[$this->count++];
                }
            }
        );

        self::assertEquals('01020300', \bin2hex(\pack('V', $randomizer->getInt(0, 0xFFFFFF))));
    }
}
