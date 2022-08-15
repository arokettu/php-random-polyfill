<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class User32 implements Engine
{
    /** @var int */
    private $count = 0;

    public function generate(): string
    {
        return \pack('V', ++$this->count);
    }
}
