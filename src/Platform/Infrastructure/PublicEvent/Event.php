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

namespace FixCity\Platform\Infrastructure\PublicEvent;

use FixCity\Component\EventBus\PublicEvent;
use FixCity\Component\Identity\Uuid;

abstract class Event extends PublicEvent
{
    /**
     * @param Uuid                                        $eventId
     * @param array<string, array<string, string>|string> $payload
     * @param \DateTimeImmutable                          $occurredAt
     */
    public function __construct(Uuid $eventId, array $payload, \DateTimeImmutable $occurredAt)
    {
        parent::__construct($eventId, static::class, $payload, $occurredAt);
    }
}
