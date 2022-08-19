<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/03_randomizer/serialize.phpt
 */
class SerializeTestUserEngine implements Engine
{
    public function generate(): string
    {
        global $___serialize_test_generate;
        return $___serialize_test_generate;
    }
}
