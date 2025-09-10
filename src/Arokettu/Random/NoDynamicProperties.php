<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random;

use Error;

/**
 * @internal
 */
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
