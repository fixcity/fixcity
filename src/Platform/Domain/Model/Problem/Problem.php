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

namespace FixCity\Platform\Domain\Model\Problem;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\EventStore\EventStream\Event;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\IncrementalId;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Event\ProblemClosed;
use FixCity\Platform\Domain\Event\ProblemFixed;
use FixCity\Platform\Domain\Event\ProblemInProgressed;
use FixCity\Platform\Domain\Event\ProblemOpened;
use FixCity\Platform\Domain\Event\ProblemReported;
use FixCity\Platform\Domain\Hydrator\ProblemHydrator;
use FixCity\Platform\Domain\Model\Collaborator\Author;
use FixCity\Platform\Domain\Model\Collaborator\Organization;
use FixCity\Platform\Domain\Model\Tenant\TenantId;

final class Problem
{
    private Uuid $problemId;

    private IncrementalId $numberId;

    private Type $type;

    private Location $location;

    private string $status;

    private Description $description;

    /**
     * @var array<int, Comment>
     */
    private array $comments;

    private TenantId $tenantId;

    private Author $author;

    private Organization $organization;

    private DateTimeImmutable $createdAt;

    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        Uuid $problemId,
        IncrementalId $numberId,
        Type $type,
        Location $location,
        Description $description,
        TenantId $tenantId,
        Author $author,
        Organization $organization,
        DateTimeImmutable $createdAt
    ) {
        $this->problemId    = $problemId;
        $this->numberId     = $numberId;
        $this->type         = $type;
        $this->location     = $location;
        $this->status       = Status::REPORTED;
        $this->description  = $description;
        $this->comments     = [];
        $this->tenantId     = $tenantId;
        $this->author       = $author;
        $this->organization = $organization;
        $this->createdAt    = $createdAt;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function recreateFrom(EventStream $eventStream): self
    {
        $apply = static function (Problem $self, Event $event): self {
            if ($event instanceof ProblemOpened) {
                $self->opened();

                return $self;
            }
            if ($event instanceof ProblemInProgressed) {
                $self->inProgress();

                return $self;
            }
            if ($event instanceof ProblemFixed) {
                $self->fixed();

                return $self;
            }
            if ($event instanceof ProblemClosed) {
                $self->closed();

                return $self;
            }

            throw InvalidArgumentException::unknownEvent($event->eventName());
        };


        $aggregate = null;
        foreach ($eventStream as $event) {
            if ($event instanceof ProblemReported) {
                $hydrator = new ProblemHydrator();
                $aggregate = $hydrator->hydrate($event->payload());

                continue;
            }

            if ($aggregate !== null) {
                $aggregate = $apply($aggregate, $event);
            }
        }

        if ($aggregate === null) {
            throw InvalidArgumentException::aggregateIsInvalid();
        }

        return $aggregate;
    }

    public function opened(): void
    {
        $this->status = Status::OPENED;
    }

    public function inProgress(): void
    {
        $this->status = Status::IN_PROGRESS;
    }

    public function fixed(): void
    {
        $this->status = Status::FIXED;
    }

    public function closed(): void
    {
        $this->status = Status::CLOSED;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function problemId(): Uuid
    {
        return $this->problemId;
    }

    public function numberId(): IncrementalId
    {
        return $this->numberId;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function description(): Description
    {
        return $this->description;
    }

    /**
     * @return array<int, Comment>
     */
    public function comments(): array
    {
        return $this->comments;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @throws Exception
     */
    public function updatedAt(): DateTimeImmutable
    {
        if ($this->updatedAt === null) {
            $this->updatedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        }

        return $this->updatedAt;
    }
}
