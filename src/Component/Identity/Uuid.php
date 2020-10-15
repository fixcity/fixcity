<?php
/**
 * This file is part of the fixcity package.
 *
 * (c) FixCity <fixcity.org@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FixCity\Component\Identity;

use Ramsey\Uuid\Uuid as BaseUUID;

final class Uuid implements \JsonSerializable
{
    private string $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @throws \Exception
     */
    public static function generate(): self
    {
        return new static(BaseUUID::uuid4()->toString());
    }

    public static function fromString(string $uuid): self
    {
        return new static(BaseUUID::fromString($uuid)->toString());
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
