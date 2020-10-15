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

namespace FixCity\Platform\Infrastructure\InMemory;

use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\EventBus\EventBus;
use FixCity\Component\EventStore\EventStream\Event;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Platform\Domain\Event\ProblemClosed;
use FixCity\Platform\Domain\Event\ProblemFixed;
use FixCity\Platform\Domain\Event\ProblemInProgressed;
use FixCity\Platform\Domain\Event\ProblemOpened;
use FixCity\Platform\Domain\Event\ProblemReported;
use FixCity\Platform\Infrastructure\PublicEvent\StatusChanged;

final class InMemoryEventStream implements EventStream
{
    /**
     * @var array<int, Event>
     */
    private array $events;

    private bool $isReceived = false;

    private string $topic;

    private EventBus $eventBus;

    public function __construct(EventBus $eventBus = null, string $topic = '')
    {
        $this->events   = [];
        $this->topic    = $topic;
        if ($eventBus === null) {
            $this->eventBus = new InMemoryDummyEventBus();

            return;
        }
        $this->eventBus = $eventBus;
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
        $this->events[]   = $event;
        $this->isReceived = false;
    }

    public function recordAll(array $events): void
    {
        $this->isReceived = true;
        $this->events     = $events;
    }

    public function flush(): void
    {
        if ($this->isReceived || $this->topic === '') {
            $this->events = [];

            return;
        }

        foreach ($this->events as $event) {
            switch (\get_class($event)) {
                case ProblemOpened::class:
                case ProblemInProgressed::class:
                case ProblemClosed::class:
                case ProblemFixed::class:
                    $publicEvent = new StatusChanged(
                        $event->eventId(),
                        $event->payload(),
                        $event->occurredAt()
                    );

                    break;
                case ProblemReported::class:
                    $publicEvent = new \FixCity\Platform\Infrastructure\PublicEvent\ProblemReported(
                        $event->eventId(),
                        $event->payload(),
                        $event->occurredAt()
                    );

                    break;
                default:
                    throw InvalidArgumentException::unknownEvent(\get_class($event));
            }
            $this->eventBus->publishTo($this->topic, $publicEvent);
        }

        $this->events = [];
    }
}
