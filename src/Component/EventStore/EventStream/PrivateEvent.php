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

class PrivateEvent implements Event
{
    private Uuid $eventId;

    private string $eventName;

    /**
     * @var array<mixed>
     */
    private array $payload;

    private \DateTimeImmutable $occurredAt;

    /**
     * PrivateEvent constructor.
     *
     * @param Uuid                                        $eventId
     * @param array<mixed> $payload
     * @param \DateTimeImmutable                          $occurredAt
     */
    public function __construct(Uuid $eventId, array $payload, \DateTimeImmutable $occurredAt)
    {
        $this->eventId    = $eventId;
        $this->eventName  = (string)get_class($this);
        $this->payload    = $payload;
        $this->occurredAt = $occurredAt;
    }

    public function eventId(): Uuid
    {
        return $this->eventId;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    /**
     * @return array<string, string>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function __toString(): string
    {
        return static::class;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
