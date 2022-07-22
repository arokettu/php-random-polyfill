<?php

declare(strict_types=1);

namespace Random\Engine;

use Exception;
use Random\CryptoSafeEngine;

use function random_bytes;

final class Secure implements CryptoSafeEngine
{
    public function generate(): string
    {
        return random_bytes(PHP_INT_SIZE);
    }

    public function __sleep(): array
    {
        throw new Exception("Serialization of 'Random\Engine\Secure' is not allowed");
    }
}
