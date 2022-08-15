<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class Fail implements Engine
{
    public function generate(): string
    {
        return '';
    }
}
