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

namespace FixCity\Platform\Application\Command;

use Assert\AssertionFailedException;
use FixCity\Component\CQRS\ClassName;
use FixCity\Component\CQRS\CommandValidator;
use FixCity\Component\CQRS\Handler;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\IncrementalId;
use FixCity\Component\Identity\Uuid;
use FixCity\Component\Mapping\Status;
use FixCity\Platform\Domain\Event\Factory\EventFactory;
use FixCity\Platform\Domain\Event\ProblemReported;
use FixCity\Platform\Domain\Model\Problem\Problem;
use FixCity\Platform\Domain\Model\Tenant\TenantId;
use FixCity\Platform\Domain\Problems;

final class ReportProblemHandler implements Handler
{
    use ClassName, CommandValidator;

    private Problems $problems;

    private EventStream $eventStream;

    public function __construct(Problems $problems, EventStream $eventStream)
    {
        $this->problems    = $problems;
        $this->eventStream = $eventStream;
    }

    /**
     * @throws \Exception
     * @throws AssertionFailedException
     */
    public function __invoke(ReportProblem $command): void
    {
        self::validCommand(Status::REPORT_PROBLEM, $command);

        $problemId = Uuid::fromString($command->problemId());
        $problem   = new Problem(
            $problemId,
            new IncrementalId($command->numberId()),
            $command->type(),
            $command->location(),
            $command->description(),
            new TenantId($command->tenantId()),
            $command->author(),
            $command->organization(),
            $command->createdAt()
        );

        $this->eventStream->record(EventFactory::createProblemEvent(ProblemReported::class, $problem));
        $this->problems->add($problemId, $this->eventStream);
    }
}
