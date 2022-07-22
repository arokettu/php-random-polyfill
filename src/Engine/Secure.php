<?php

declare(strict_types=1);

namespace Random\Engine;

use Random\CryptoSafeEngine;

final class Secure implements CryptoSafeEngine
{
    public function generate(): string
    {
        return random_bytes(PHP_INT_SIZE);
    }
}
