<?php

/**
 * @copyright Copyright © 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random;

use Exception;

use function serialize;
use function trigger_error;
use function unserialize;

/**
 * @internal
 */
trait Serialization
{
    abstract protected function getStates(): array;
    abstract protected function loadStates(array $states): bool;
    abstract protected function initConst(): void;

    public function serialize(): string
    {
        trigger_error('Serialized object will be incompatible with PHP 8.2', E_USER_WARNING);
        return serialize($this->__serialize());
    }

    /**
     * @param string $data
     * @throws Exception
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function unserialize($data): void
    {
        $this->__unserialize(unserialize($data));
    }

    public function __serialize(): array
    {
        return [[], $this->getStates()];
    }

    /**
     * @throws Exception
     */
    public function __unserialize(array $data): void
    {
        $this->initConst();
        $result = $this->loadStates($data[1] ?? []);
        if ($result === false) {
            throw new Exception(sprintf('Invalid serialization data for %s object', static::class));
        }
    }

    public function __debugInfo(): array
    {
        return ['__states' => $this->getStates()];
    }
}
