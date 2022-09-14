<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_32.phpt
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/methods/getInt_expansion_64.phpt
 */
class ByteEngine implements Engine
{
    /** @var int */
    public $count = 0;

    public function generate(): string
    {
        return "\x01\x02\x03\x04\x05\x06\x07\x08"[$this->count++];
    }
}
