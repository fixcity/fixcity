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

namespace FixCity\Platform\Infrastructure\Persistence\Factory;

use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\CQRS\Projection;
use FixCity\Component\Database\DatabaseAdapter;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Platform\Infrastructure\Persistence\DatabaseReportProblemProjection;
use FixCity\Platform\Infrastructure\PublicEvent\ProblemReported;
use FixCity\Platform\Infrastructure\PublicEvent\StatusChanged;

final class ProjectionFactory
{
    private function __construct()
    {
    }

    public static function createProjection(string $eventName, DatabaseAdapter $database): Projection
    {
        switch ($eventName) {
            case ProblemReported::class:
                return new DatabaseReportProblemProjection($database);
            case StatusChanged::class:
                return new class implements Projection {
                    public function __invoke(PublicEvent $event): void
                    {
                        $event->eventName();
                    }
                };
            default:
                throw InvalidArgumentException::unknownProjectionForEvent($eventName);
        }
    }
}
