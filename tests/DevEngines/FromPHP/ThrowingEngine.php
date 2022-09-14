<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Exception;
use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_throws.phpt
 */
class ThrowingEngine implements Engine
{
    public function generate(): string
    {
        throw new Exception('Error');
    }
}
