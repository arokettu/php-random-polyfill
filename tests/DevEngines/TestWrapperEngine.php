<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

use Random\Engine;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/engines.inc
 */
class TestWrapperEngine implements Engine
{
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function generate(): string
    {
        return $this->engine->generate();
    }
}
