<?php

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class Zeros implements Engine
{
    public function generate(): string
    {
        return "\0\0\0\0";
    }
}
