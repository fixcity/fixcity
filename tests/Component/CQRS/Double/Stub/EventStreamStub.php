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

namespace FixCity\Component\CQRS\Double\Stub;

use FixCity\Component\EventStore\EventStream\Event;
use FixCity\Component\EventStore\EventStream\EventStream;

final class EventStreamStub implements EventStream
{
    /**
     * @var array<int, Event> $events
     */
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    /**
     * @return \Iterator<int, Event>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->events);
    }

    public function record(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @inheritDoc
     */
    public function recordAll(array $events): void
    {
        $this->events = $events;
    }

    public function flush(): void
    {
        $this->events = [];
    }
}
