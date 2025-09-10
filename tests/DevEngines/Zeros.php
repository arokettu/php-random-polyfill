<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class Zeros implements Engine
{
    public function generate(): string
    {
        return "\0\0\0\0";
    }
}
