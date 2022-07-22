<?php

declare(strict_types=1);

namespace Random\Engine;

use Random\Engine;

final class Secure implements Engine
{
    public function generate(): string
    {
        return random_bytes(PHP_INT_SIZE);
    }
}
