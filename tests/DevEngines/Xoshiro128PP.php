<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests\DevEngines;

/**
 * @see https://github.com/php/php-src/blob/master/ext/random/tests/02_engine/user_xoshiro128plusplus.phpt
 */
final class Xoshiro128PP implements \Random\Engine
{
    private $s0;
    private $s1;
    private $s2;
    private $s3;

    private function __construct(int $s0, int $s1, int $s2, int $s3)
    {
        $this->s0 = $s0;
        $this->s1 = $s1;
        $this->s2 = $s2;
        $this->s3 = $s3;
    }

    public static function fromNumbers(int $s0, int $s1, int $s2, int $s3): self
    {
        return new self($s0, $s1, $s2, $s3);
    }

    private static function rotl(int $x, int $k): int
    {
        return (($x << $k) | ($x >> (32 - $k))) & 0xFFFFFFFF;
    }

    public function generate(): string
    {
        $result = (self::rotl(($this->s0 + $this->s3) & 0xFFFFFFFF, 7) + $this->s0) & 0xFFFFFFFF;

        $t = ($this->s1 << 9)  & 0xFFFFFFFF;

        $this->s2 ^= $this->s0;
        $this->s3 ^= $this->s1;
        $this->s1 ^= $this->s2;
        $this->s0 ^= $this->s3;

        $this->s2 ^= $t;

        $this->s3 = self::rotl($this->s3, 11);

        return \pack('V', $result);
    }
}
