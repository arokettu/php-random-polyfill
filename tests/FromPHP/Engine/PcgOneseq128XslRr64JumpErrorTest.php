<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use RuntimeException;
use Throwable;
use ValueError;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/pcgoneseq128xslrr64_jump_error.phpt
 */
class PcgOneseq128XslRr64JumpErrorTest extends TestCase
{
    public function testNoNegJump(): void
    {
        try {
            $engine = new PcgOneseq128XslRr64(1234);
            $referenceEngine = new PcgOneseq128XslRr64(1234);

            $engine->jump(-1);
        } catch (Throwable $e) {
            self::assertEquals(ValueError::class, get_class($e));
            self::assertEquals(
                'Random\Engine\PcgOneseq128XslRr64::jump():' .
                ' Argument #1 ($advance) must be greater than or equal to 0',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            // also state must not change
            self::assertEquals($referenceEngine->generate(), $engine->generate());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
