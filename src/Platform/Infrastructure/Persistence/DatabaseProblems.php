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

use Assert\AssertionFailedException;
use FixCity\Component\EventStore\EventStore;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Model\Problem\Problem;
use FixCity\Platform\Domain\Problems;

final class DatabaseProblems implements Problems
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @throws AssertionFailedException
     */
    public function getById(Uuid $problemId): Problem
    {
        return Problem::recreateFrom($this->eventStore->loadEventStream($problemId));
    }

    public function add(Uuid $problemId, EventStream $eventStream): void
    {
        $this->eventStore->store($problemId, $eventStream);
    }

    /**
     * @throws \Exception
     */
    public function nextIdentity(): Uuid
    {
        return Uuid::generate();
    }
}
