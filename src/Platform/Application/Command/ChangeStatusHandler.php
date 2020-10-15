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

use FixCity\Component\CQRS\ClassNameGenerator;
use FixCity\Component\CQRS\CommandValidator;
use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\CQRS\Handler;
use FixCity\Component\CQRS\MethodNameGenerator;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Event\Factory\EventFactory;
use FixCity\Platform\Domain\Problems;

final class ChangeStatusHandler implements Handler
{
    use CommandValidator;

    private Problems $problems;

    private EventStream $eventStream;

    private string $status;

    public function __construct(Problems $problems, EventStream $eventStream, string $status)
    {
        $this->problems    = $problems;
        $this->eventStream = $eventStream;
        $this->status      = $status;
    }

    /**
     * @param OpenProblem|InProgressProblem|CloseProblem|FixProblem $command
     *
     * @throws \Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(object $command): void
    {
        self::validCommand($this->status, $command);

        $problemId = Uuid::fromString($command->problemId());
        $problem = $this->problems->getById($problemId);
        $methodName = (new MethodNameGenerator($this->status))->methodName();
        $problem->{$methodName}();

        $this->eventStream->record(EventFactory::createProblemEvent(
            (new ClassNameGenerator($this->status))->eventClassName(),
            $problem
        ));
        $this->problems->add($problemId, $this->eventStream);
    }

    public function __toString(): string
    {
        return (new ClassNameGenerator($this->status))->handlerClassName();
    }
}
