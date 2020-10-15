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

namespace FixCity\Component\CQRS;

use FixCity\Component\EventStore\EventStream\EventStream;

final class System
{
    private CommandBus $commandBus;

    private QueryBus $queryBus;

    private EventStream $eventStream;

    private ?Projector $projector;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        EventStream $eventStream,
        ?Projector $projector = null
    ) {
        $this->commandBus  = $commandBus;
        $this->queryBus    = $queryBus;
        $this->eventStream = $eventStream;
        $this->projector   = $projector;
    }

    /**
     * @throws \Throwable
     */
    public function handle(Command $command): void
    {
        try {
            $this->commandBus->handle($command);
            if ($this->projector !== null) {
                $this->projector->registerToTopic();
            }
            $this->eventStream->flush();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function query(string $queryName): Query
    {
        return $this->queryBus->handle($queryName);
    }
}
