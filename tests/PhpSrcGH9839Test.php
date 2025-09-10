<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * @see https://github.com/php/php-src/pull/9839
 * @see https://github.com/arokettu/php-random-polyfill/issues/4#issuecomment-1293353629
 */
class PhpSrcGH9839Test extends TestCase
{
    // just so we don't break the regular algo
    public function testShuffleBytes(): void
    {
        $digits = '0123456789';
        $expected = '0827613495';

        $r = new Randomizer(new Mt19937(1, \MT_RAND_MT19937));

        $s = $r->shuffleBytes($digits);

        self::assertEquals($expected, $s);
    }

    public function testShuffleBytesCompat(): void
    {
        $digits = '0123456789';
        $expected = '8926013475'; // not 8132476905

        $r = new Randomizer(new Mt19937(1, \MT_RAND_PHP));

        $s = $r->shuffleBytes($digits);

        self::assertEquals($expected, $s);
    }

    // just so we don't break the regular algo
    public function testShuffleArray(): void
    {
        $digits = \str_split('0123456789');
        $expected = \str_split('0827613495');

        $r = new Randomizer(new Mt19937(1, \MT_RAND_MT19937));

        $s = $r->shuffleArray($digits);

        self::assertEquals($expected, $s);
    }

    public function testShuffleArrayCompat(): void
    {
        $digits = \str_split('0123456789');
        $expected = \str_split('8926013475'); // not 8132476905

        $r = new Randomizer(new Mt19937(1, \MT_RAND_PHP));

        $s = $r->shuffleArray($digits);

        self::assertEquals($expected, $s);
    }
}
