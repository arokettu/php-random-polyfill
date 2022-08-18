<?php

declare(strict_types=1);

namespace Random;

use Arokettu\Random\NoDynamicProperties;
use Exception;

class RandomException extends Exception
{
    use NoDynamicProperties;
}
