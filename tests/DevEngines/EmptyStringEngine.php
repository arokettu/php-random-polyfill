<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/user_unsafe.phpt
 */
final class EmptyStringEngine implements Engine
{
    public function generate(): string
    {
        return '';
    }
}
