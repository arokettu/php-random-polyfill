<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Engine;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/get_bytes.phpt
 */
class GetBytesTest extends TestCase
{
    public function testGetBytes(): void
    {
        $randomizer = new Randomizer(
            new class () implements Engine
            {
                /** @var int */
                private $count = 0;

                public function generate(): string
                {
                    if ($this->count > 5) {
                        throw new \RuntimeException('overflow');
                    }

                    switch ($this->count++) {
                        case 0:
                            return 'H';
                        case 1:
                            return 'e';
                        case 2:
                            return 'll';
                        case 3:
                            return 'o';
                        case 4:
                            return 'abcdefghijklmnopqrstuvwxyz';
                        case 5:
                            return 'success';
                        default:
                            return \random_bytes(16);
                    }
                }
            }
        );

        self::assertEquals('Hello', $randomizer->getBytes(5));
        self::assertEquals('abcdefgh', $randomizer->getBytes(8)); // 64 bits
        self::assertEquals('success', $randomizer->getBytes(7)); // 64 bits
    }
}
