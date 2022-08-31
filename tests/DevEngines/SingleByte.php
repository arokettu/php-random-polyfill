<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

final class SingleByte implements Engine
{
    /**
     * @var int
     */
    private $chr;

    /**
     * @param int|string $value
     */
    public function __construct($value = 0)
    {
        if (\is_int($value)) {
            $value = \abs($value) % 256;
        } else {
            $value = \ord(\strval($value));
        }

        $this->chr = $value;
    }

    public function generate(): string
    {
        if ($this->chr >= 256) {
            $this->chr = 0;
        }

        return \chr($this->chr++);
    }
}
