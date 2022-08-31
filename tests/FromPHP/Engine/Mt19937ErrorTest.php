<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use RuntimeException;
use Throwable;
use ValueError;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/mt19937_error.phpt
 */
class Mt19937ErrorTest extends TestCase
{
    public function testMtError(): void
    {
        try {
            new Mt19937(1234, 2);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, \get_class($e));
            self::assertEquals(
                'Random\Engine\Mt19937::__construct():' .
                    ' Argument #2 ($mode) must be either MT_RAND_MT19937 or MT_RAND_PHP',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
