<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-2-Clause https://spdx.org/licenses/BSD-2-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class Xorshift32 implements Engine
{
    /** @var int */
    private $seed;

    public function __construct(int $seed)
    {
        $this->seed = $seed;
    }

    public function generate(): string
    {
        // normalize seed
        $this->seed = $this->seed & 0x7fffffff ?: 1;
        // do shifts
        $this->seed ^= ($this->seed << 13) & 0x7fffffff;
        $this->seed ^= ($this->seed >> 17);
        $this->seed ^= ($this->seed << 5) & 0x7fffffff;

        return \pack('V', $this->seed);
    }
}
