<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines\FromPHP;

use Random\Engine;

class TestGetBytesEngine implements Engine
{
    /** @var int */
    private $count = 0;

    public function generate(): string
    {
        switch ($this->count++) {
            case 0:
                return 'H';
            case 1:
                return 'e';
            case 2:
                return 'll';
            case 3:
                return 'o';
            case 4:
                return 'abcdefghijklmnopqrstuvwxyz';
            case 5:
                return 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            case 6:
                return 'success';
            default:
                throw new \Exception('Unhandled');
        }
    }
}
