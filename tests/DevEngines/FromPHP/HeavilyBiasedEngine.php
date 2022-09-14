<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_biased.phpt
 */
class HeavilyBiasedEngine implements Engine
{
    public function generate(): string
    {
        return \str_repeat("\xff", \PHP_INT_SIZE);
    }
}
