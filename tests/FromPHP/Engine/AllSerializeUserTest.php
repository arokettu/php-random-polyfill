<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use Arokettu\Random\Tests\DevEngines\User32;
use Arokettu\Random\Tests\DevEngines\User64;
use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/all_serialize_user.phpt
 */
class AllSerializeUserTest extends TestCase
{
    public function testSerialize(): void
    {
        $engines = [];
        if (\PHP_INT_SIZE >= 8) {
            $engines[] = new User64();
        }
        $engines[] = new User32();

        foreach ($engines as $engine) {
            for ($i = 0; $i < 1000; $i++) {
                $engine->generate();
            }
            $engine2 = unserialize(@serialize($engine));
            for ($i = 0; $i < 5000; $i++) {
                self::assertEquals($engine->generate(), $engine2->generate());
            }
        }
    }
}
