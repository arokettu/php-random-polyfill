<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 *
 * Includes test code from the PHP Interpreter
 * @copyright 1999-2012 The PHP Group. All rights reserved.
 * @license PHP-3.01 https://spdx.org/licenses/PHP-3.01.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use Arokettu\Random\Tests\DevEngines\FromPHP\TestShaEngine;
use Error;
use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use RuntimeException;
use Throwable;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/construct_twice.phpt
 */
final class ConstructTwiceTest extends TestCase
{
    public function test1(): void
    {
        try {
            (new Randomizer())->__construct();
        } catch (Throwable $e) {
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function test2(): void
    {
        try {
            $r = new Randomizer(new Xoshiro256StarStar());
            $r->__construct(new PcgOneseq128XslRr64());
        } catch (Throwable $e) {
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            self::assertEquals(Xoshiro256StarStar::class, \get_class($r->engine));

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function test3(): void
    {
        try {
            $r = new Randomizer(new TestShaEngine('1234'));
            $r->__construct(new TestShaEngine('1234'));
        } catch (Throwable $e) {
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function test4(): void
    {
        try {
            $r = new Randomizer(new Xoshiro256StarStar());
            $r->__construct(new TestShaEngine('1234'));
        } catch (Throwable $e) {
            self::assertEquals(
                'Cannot modify readonly property Random\Randomizer::$engine',
                $e->getMessage()
            );
            self::assertEquals(Error::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            self::assertEquals(Xoshiro256StarStar::class, \get_class($r->engine));

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
