<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

/**
 * These won't work in PHP 8.2+ but still check if they are compatible
 */
class LegacySerializationTest extends TestCase
{
    public function testLegacySerialization(): void
    {
        if (\PHP_VERSION_ID >= 80200) {
            $this->markTestSkipped('Native implementation is incompatible');
        }

        $engine1 = new Mt19937(null, \MT_RAND_MT19937);
        $engine2 = new Mt19937(null, \MT_RAND_MT19937);
        $engine2->unserialize(@$engine1->serialize());

        self::assertEquals($engine1->generate(), $engine2->generate());

        $engine1 = new Mt19937(null, \MT_RAND_PHP);
        $engine2 = new Mt19937(null, \MT_RAND_PHP);
        $engine2->unserialize(@$engine1->serialize());

        self::assertEquals($engine1->generate(), $engine2->generate());

        $engine1 = new PcgOneseq128XslRr64();
        $engine2 = new PcgOneseq128XslRr64();
        $engine2->unserialize(@$engine1->serialize());

        self::assertEquals($engine1->generate(), $engine2->generate());

        $engine1 = new Xoshiro256StarStar();
        $engine2 = new Xoshiro256StarStar();
        $engine2->unserialize(@$engine1->serialize());

        self::assertEquals($engine1->generate(), $engine2->generate());

        $rnd1 = new Randomizer(new Xoshiro256StarStar());
        $rnd2 = new Randomizer();
        $rnd2->unserialize(@$rnd1->serialize());

        self::assertEquals($rnd1->getBytes(8), $rnd2->getBytes(8));
    }

    public function testKnownLegacyUnserialize(): void
    {
        if (\PHP_VERSION_ID >= 80200) {
            $this->markTestSkipped('Native implementation is incompatible');
        }

        $serializedPCG =
            'C:33:"Random\Engine\PcgOneseq128XslRr64":82:{a:2:{i:0;a:0:{}i:1;a:2:{i:0;s:16:"401892524857b9cc";' .
            'i:1;s:16:"c6de1a2570db7cba";}}}';
        $pcg = \unserialize($serializedPCG);
        self::assertEquals('0af2f765e8de4aef', \bin2hex($pcg->generate()));

        $serializedRnd =
            'C:17:"Random\Randomizer":213:{a:1:{i:0;a:1:{s:6:"engine";C:32:"Random\Engine\Xoshiro256StarStar":138:' .
            '{a:2:{i:0;a:0:{}i:1;a:4:{i:0;s:16:"87a2f5dbb2d2eee3";i:1;s:16:"cc742518a23d8bd0";i:2;s:16:"29075c860e' .
            '3e0b29";i:3;s:16:"9e75d6b6208aa712";}}}}}}';
        $rnd = \unserialize($serializedRnd);
        self::assertEquals('51eec34a1fbfea3c', \bin2hex($rnd->getBytes(8)));
    }
}
