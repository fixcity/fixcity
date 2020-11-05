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

namespace FixCity\Component\EventBus;

use FixCity\Component\Identity\Uuid;

class PublicEvent
{
    private Uuid $eventId;

    private string $eventName;

    /**
     * @var array<string, array<string, string>|string>
     */
    private array $payload;

    private \DateTimeImmutable $occurredAt;

    /**
     * @param Uuid                                        $eventId
     * @param string                                      $eventName
     * @param array<string, array<string, string>|string> $payload
     * @param \DateTimeImmutable                          $occurredAt
     */
    public function __construct(Uuid $eventId, string $eventName, array $payload, \DateTimeImmutable $occurredAt)
    {
        $this->eventId    = $eventId;
        $this->eventName  = $eventName;
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
     * @return array<string, array<string, string>|string>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
