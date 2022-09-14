<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use Arokettu\Random\Tests\DevEngines\FromPHP\CountingEngine32;
use Arokettu\Random\Tests\DevEngines\FromPHP\TestShaEngine;
use PHPUnit\Framework\TestCase;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/all_serialize_user.phpt
 */
class AllSerializeUserTest extends TestCase
{
    public function testSerialize(): void
    {
        $engines = [];
        $engines[] = new CountingEngine32();
        $engines[] = new TestShaEngine();

        foreach ($engines as $engine) {
            for ($i = 0; $i < 10000; $i++) {
                $engine->generate();
            }
            $engine2 = \unserialize(@\serialize($engine));
            for ($i = 0; $i < 10000; $i++) {
                self::assertEquals($engine->generate(), $engine2->generate());
            }
        }
    }
}
