<?php

declare(strict_types=1);

namespace Random;

interface Engine
{
    public function generate(): string;
}
