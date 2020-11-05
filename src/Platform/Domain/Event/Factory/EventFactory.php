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

namespace FixCity\Platform\Domain\Event\Factory;

use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\EventStore\EventStream\Event;
use FixCity\Component\EventStore\EventStream\PrivateEvent;
use FixCity\Component\Identity\Uuid;
use FixCity\Component\Mapping\CommentFields;
use FixCity\Component\Mapping\ProblemFields;
use FixCity\Platform\Domain\Event\CommentAdded;
use FixCity\Platform\Domain\Event\ProblemClosed;
use FixCity\Platform\Domain\Event\ProblemFixed;
use FixCity\Platform\Domain\Event\ProblemInProgressed;
use FixCity\Platform\Domain\Event\ProblemOpened;
use FixCity\Platform\Domain\Event\ProblemReported;
use FixCity\Platform\Domain\Hydrator\ProblemHydrator;
use FixCity\Platform\Domain\Model\Problem\Comment;
use FixCity\Platform\Domain\Model\Problem\Problem;

final class EventFactory
{
    private function __construct()
    {
    }

    /**
     * @param string[] $payload
     *
     * @return Event
     * @throws \Exception
     */
    public static function createEventFromPayload(array $payload): Event
    {
        /** @var string[] $eventId */
        $eventId = $payload['eventId'];
        /** @var string[] $occurredAt */
        $occurredAt = $payload['occurredAt'];
        $event      = new $payload['eventName'](
            Uuid::fromString($eventId['uuid']),
            $payload['payload'],
            new \DateTimeImmutable(
                $occurredAt['date'],
                new \DateTimeZone($occurredAt['timezone'])
            )
        );

        if ($event instanceof Event) {
            return $event;
        }

        throw InvalidArgumentException::unknownEvent($payload['eventName']);
    }

    /**
     * @throws \Exception
     */
    public static function createProblemEvent(string $eventName, Problem $problem): Event
    {
        $statusPayload = [ProblemFields::STATUS => $problem->status()];
        $occurredAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        switch ($eventName) {
            case ProblemReported::class:
                $hydrator   = new ProblemHydrator();
                $payload    = $hydrator->extract($problem);

                return new ProblemReported(Uuid::generate(), $payload, $occurredAt);
            case ProblemOpened::class:
                return new ProblemOpened(Uuid::generate(), $statusPayload, $occurredAt);
            case ProblemInProgressed::class:
                return new ProblemInProgressed(Uuid::generate(), $statusPayload, $occurredAt);
            case ProblemClosed::class:
                return new ProblemClosed(Uuid::generate(), $statusPayload, $occurredAt);
            case ProblemFixed::class:
                return new ProblemFixed(Uuid::generate(), $statusPayload, $occurredAt);
            default:
                return new PrivateEvent(Uuid::generate(), $statusPayload, $occurredAt);
        }
    }

    /**
     * @throws \Exception
     */
    public static function createCommentEvent(Comment $comment): Event
    {
        $payload    = [
            CommentFields::COMMENT_ID => (string)$comment->commentId(),
            CommentFields::PROBLEM_ID => (string)$comment->problemId(),
            CommentFields::AUTHOR => [
                'identity' => $comment->author()->identity(),
                'name' => $comment->author()->name(),
                'emailAddress' => $comment->author()->emailAddress(),
            ],
            CommentFields::DESCRIPTION => $comment->description(),
        ];
        $occurredAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        return new CommentAdded(Uuid::generate(), $payload, $occurredAt);
    }
}
