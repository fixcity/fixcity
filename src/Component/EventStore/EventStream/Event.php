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

namespace FixCity\Component\EventStore\EventStream;

use FixCity\Component\Identity\Uuid;

interface Event extends \JsonSerializable
{
    public function eventId(): Uuid;

    public function eventName(): string;

    /**
     * @return string[]
     */
    public function payload(): array;

    public function occurredAt(): \DateTimeImmutable;

    public function __toString(): string;
}
