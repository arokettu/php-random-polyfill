<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests;

trait AssertRegexCompat
{
    public static function assertRegex(string $pattern, string $string, string $message = ''): void
    {
        if (\method_exists(static::class, 'assertMatchesRegularExpression')) {
            self::assertMatchesRegularExpression($pattern, $string, $message);
            return;
        }

        if (\method_exists(static::class, 'assertRegExp')) {
            self::assertRegExp($pattern, $string, $message);
            return;
        }

        throw new \LogicException('Class should have either assertMatchesRegularExpression or assertRegExp');
    }
}
