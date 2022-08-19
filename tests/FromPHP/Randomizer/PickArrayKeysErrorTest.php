<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Randomizer;

use PHPUnit\Framework\TestCase;
use Random\Randomizer;
use RuntimeException;
use Throwable;
use ValueError;
use function PHPUnit\Framework\assertCount;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/pick_array_keys_error.phpt
 */
class PickArrayKeysErrorTest extends TestCase
{
    public function testEmptyArray(): void
    {
        try {
            (new Randomizer())->pickArrayKeys([], 0);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #1 ($array) cannot be empty',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testZero(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(range(1, 3), 0);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testNeg(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(range(1, 3), -1);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testTooBig(): void
    {
        try {
            (new Randomizer())->pickArrayKeys(range(1, 3), 10);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Randomizer::pickArrayKeys():' .
                ' Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }

    public function testSuccess(): void
    {
        $r = (new Randomizer())->pickArrayKeys(range(1, 3), 3);
        self::assertCount(3, $r);
        $r = (new Randomizer())->pickArrayKeys(range(1, 3), 2);
        self::assertCount(2, $r);
    }
}
