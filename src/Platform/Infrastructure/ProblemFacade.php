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

namespace FixCity\Platform\Infrastructure;

use Doctrine\DBAL\Connection;
use FixCity\Component\CQRS\CommandBus;
use FixCity\Component\CQRS\Projection;
use FixCity\Component\CQRS\QueryBus;
use FixCity\Component\CQRS\System;
use FixCity\Component\EventBus\EventBus;
use FixCity\Component\Mapping\Status;
use FixCity\Platform\Application\Command\ChangeStatusHandler;
use FixCity\Platform\Application\Command\ReportProblemHandler;
use FixCity\Platform\Infrastructure\InMemory\InMemoryEventStream;
use FixCity\Platform\Infrastructure\Persistence\DatabaseEventStore;
use FixCity\Platform\Infrastructure\Persistence\DatabaseProblems;
use FixCity\Platform\Infrastructure\Persistence\DatabaseProjector;
use FixCity\Platform\Infrastructure\Persistence\Dbal\DbalDatabaseAdapter;
use FixCity\Platform\Infrastructure\Persistence\Dbal\DbalTransactionManager;

final class ProblemFacade
{
    /**
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function __invoke(Connection $connection, EventBus $eventBus): Problems
    {
        $eventsTableConnection   = new DbalDatabaseAdapter($connection, DatabaseEventStore::EVENTS_TABLE);
        $problemsTableConnection = new DbalDatabaseAdapter($connection, Projection::PROBLEMS_TABLE);
        $problems                = new DatabaseProblems(new DatabaseEventStore($eventsTableConnection));
        $eventStream             = new InMemoryEventStream($eventBus, EventBus::TOPIC_PROBLEMS);

        return new Problems(
            new System(
                new CommandBus(
                    new DbalTransactionManager($connection),
                    [
                        new ReportProblemHandler($problems, $eventStream),
                        new ChangeStatusHandler($problems, $eventStream, Status::OPEN_STATUS),
                        new ChangeStatusHandler($problems, $eventStream, Status::IN_PROGRESS_STATUS),
                        new ChangeStatusHandler($problems, $eventStream, Status::CLOSE_STATUS),
                        new ChangeStatusHandler($problems, $eventStream, Status::FIX_STATUS),
                    ]
                ),
                new QueryBus(),
                $eventStream,
                new DatabaseProjector(
                    EventBus::TOPIC_PROBLEMS,
                    $eventBus,
                    $problemsTableConnection
                )
            ),
            $problems
        );
    }
}
