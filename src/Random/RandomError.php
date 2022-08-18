<?php

declare(strict_types=1);

namespace Random;

use Arokettu\Random\NoDynamicProperties;
use Error;

class RandomError extends Error
{
    use NoDynamicProperties;
}
