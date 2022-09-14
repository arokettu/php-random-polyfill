<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/engine_unsafe_empty_string.phpt
 */
class EmptyStringEngine implements Engine
{
    public function generate(): string
    {
        return '';
    }
}
