<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class User64 implements Engine
{
    /** @var int */
    private $count = 0;

    public function generate(): string
    {
        return \pack('P*', ++$this->count);
    }
}
