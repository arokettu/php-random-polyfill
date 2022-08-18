<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/basic.phpt
 */
class UserEngine implements Engine
{
    public function generate(): string
    {
        return \random_bytes(16);
    }
}
