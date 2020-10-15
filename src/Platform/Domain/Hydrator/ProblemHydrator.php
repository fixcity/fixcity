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

namespace FixCity\Platform\Domain\Hydrator;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use Exception;
use FixCity\Component\Identity\IncrementalId;
use FixCity\Component\Identity\Uuid;
use FixCity\Component\Mapping\ProblemFields;
use FixCity\Platform\Domain\Model\Collaborator\Author;
use FixCity\Platform\Domain\Model\Collaborator\Organization;
use FixCity\Platform\Domain\Model\Problem\Attachment;
use FixCity\Platform\Domain\Model\Problem\Comment;
use FixCity\Platform\Domain\Model\Problem\Description;
use FixCity\Platform\Domain\Model\Problem\Location;
use FixCity\Platform\Domain\Model\Problem\Problem;
use FixCity\Platform\Domain\Model\Problem\Type;
use FixCity\Platform\Domain\Model\Tenant\TenantId;

final class ProblemHydrator
{
    /**
     * @param Problem $problem
     *
     * @return array<string, mixed>
     */
    public function extract(Problem $problem): array
    {
        $attachments = [];
        if (!empty($problem->description()->attachments())) {
            /** @var Attachment $attachment */
            foreach ($problem->description()->attachments() as $attachment) {
                $attachments[] = [
                    'attachmentId' => (string)$attachment->attachmentId(),
                    'problemId' => (string)$attachment->problemId(),
                    'path' => $attachment->path(),
                ];
            }
        }

        $comments = [];
        if (!empty($problem->comments())) {
            /** @var Comment $comment */
            foreach ($problem->comments() as $comment) {
                $comments[] = [
                    'commentId' => (string)$comment->commentId(),
                    'problemId' => (string)$comment->problemId(),
                    'author' => [
                        'identity' => $comment->author()->identity(),
                        'name' => $comment->author()->name(),
                        'emailAddress' => $comment->author()->emailAddress(),
                    ],
                    'description' => $comment->description(),
                ];
            }
        }

        return [
            ProblemFields::PROBLEM_ID => (string)$problem->problemId(),
            ProblemFields::NUMBER_ID => $problem->numberId()->id(),
            ProblemFields::TYPE => [
                'typeId' => $problem->type()->typeId()->id(),
                'type' => $problem->type()->type(),
            ],
            ProblemFields::LOCATION => [
                'countryCode' => $problem->location()->countryCode(),
                'city' => $problem->location()->city(),
                'address' => $problem->location()->address(),
                'lat' => $problem->location()->lat(),
                'lng' => $problem->location()->lng(),
            ],
            ProblemFields::STATUS => $problem->status(),
            ProblemFields::DESCRIPTION => [
                'description' => $problem->description()->description(),
                'attachments' => $attachments,
            ],
            ProblemFields::COMMENTS => $comments,
            ProblemFields::TENANT_ID => (string)$problem->tenantId(),
            ProblemFields::AUTHOR => [
                'identity' => $problem->author()->identity(),
                'name' => $problem->author()->name(),
                'emailAddress' => $problem->author()->emailAddress(),
            ],
            ProblemFields::ORGANIZATION => [
                'identity' => $problem->organization()->identity(),
                'name' => $problem->organization()->name(),
                'emailAddress' => $problem->organization()->emailAddress(),
            ],
            ProblemFields::CREATED_AT => $problem->createdAt()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @throws AssertionFailedException
     * @throws Exception
     */
    public function hydrate(array $payload): Problem
    {
        return new Problem(
            Uuid::fromString($payload[ProblemFields::PROBLEM_ID]),
            new IncrementalId($payload[ProblemFields::NUMBER_ID]),
            new Type(new IncrementalId($payload[ProblemFields::TYPE]['typeId']), $payload[ProblemFields::TYPE]['type']),
            new Location(
                $payload[ProblemFields::LOCATION]['countryCode'],
                $payload[ProblemFields::LOCATION]['city'],
                $payload[ProblemFields::LOCATION]['address'],
                $payload[ProblemFields::LOCATION]['lat'],
                $payload[ProblemFields::LOCATION]['lng']
            ),
            new Description(
                $payload[ProblemFields::DESCRIPTION]['description'],
                $payload[ProblemFields::DESCRIPTION]['attachments']
            ),
            new TenantId($payload[ProblemFields::TENANT_ID]),
            new Author(
                $payload[ProblemFields::AUTHOR]['identity'],
                $payload[ProblemFields::AUTHOR]['name'],
                $payload[ProblemFields::AUTHOR]['emailAddress']
            ),
            new Organization(
                $payload[ProblemFields::ORGANIZATION]['identity'],
                $payload[ProblemFields::ORGANIZATION]['name'],
                $payload[ProblemFields::ORGANIZATION]['emailAddress']
            ),
            new DateTimeImmutable($payload[ProblemFields::CREATED_AT])
        );
    }
}
