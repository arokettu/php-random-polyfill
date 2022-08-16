<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\FromPHP\Engine;

use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/pcgoneseq128xslrr64_serialize.phpt
 */
class PcgOneseq128XslRr64SerializeTest extends TestCase
{
    public function testSerialize(): void
    {
        $s = serialize(new PcgOneseq128XslRr64(1234));

        self::assertEquals(
            'O:33:"Random\Engine\PcgOneseq128XslRr64":2:{i:0;a:0:{}i:1;a:2:{i:0;s:16:"c6d571c37c41a8d1";' .
                'i:1;s:16:"345e7e82265d6e27";}}',
            $s
        );
    }
}
