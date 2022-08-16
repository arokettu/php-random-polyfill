<?php

declare(strict_types=1);

namespace Arokettu\Random;

use Error;

trait NoDynamicProperties
{
    /**
     * @param mixed $value
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
    public function __set(string $name, $value): void
    {
        throw new Error('Cannot create dynamic property ' . self::class . '::$' . $name);
    }
}
