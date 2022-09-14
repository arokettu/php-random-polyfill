<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/all_serialize_native.phpt
 */
class AllSerializeNativeTest extends TestCase
{
    public function testSerialize(): void
    {
        $engines = [];
        $engines[] = new Mt19937(1234);
        $engines[] = new PcgOneseq128XslRr64(1234);
        $engines[] = new Xoshiro256StarStar(1234);

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
