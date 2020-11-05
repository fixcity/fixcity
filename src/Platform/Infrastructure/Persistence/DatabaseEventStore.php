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

namespace FixCity\Platform\Infrastructure\Persistence;

use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\Database\DatabaseAdapter;
use FixCity\Component\EventStore\EventStore;
use FixCity\Component\EventStore\EventStream\Event;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Event\Factory\EventFactory;
use FixCity\Platform\Infrastructure\InMemory\InMemoryEventStream;

final class DatabaseEventStore implements EventStore
{
    public const EVENTS_TABLE = 'fixcity_events';

    private DatabaseAdapter $database;

    public function __construct(DatabaseAdapter $database)
    {
        $this->database = $database;
    }

    /**
     * @throws \Exception
     */
    public function loadEventStream(Uuid $aggregateId): EventStream
    {
        $currentStream = new InMemoryEventStream();

        $eventsPayload = $this->database->fetchAll(
            'SELECT * FROM `' . self::EVENTS_TABLE . '` WHERE aggregate_id = ?',
            [(string)$aggregateId]
        );

        if (empty($eventsPayload)) {
            throw InvalidArgumentException::aggregateNotExists((string)$aggregateId);
        }

        $events = [];
        foreach ($eventsPayload as $eventPayload) {
            $payload = json_decode($eventPayload['event_data'], true, 512, JSON_THROW_ON_ERROR);
            $events[] = EventFactory::createEventFromPayload($payload);
        }

        $currentStream->recordAll($events);

        return $currentStream;
    }

    /**
     * @throws \Exception
     */
    public function store(Uuid $aggregateId, EventStream $events): void
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $this->database->insert([
                'aggregate_id' => (string)$aggregateId,
                'event_type' => (string)$event,
                'event_data' => json_encode($event, JSON_THROW_ON_ERROR),
            ]);
        }
    }
}
