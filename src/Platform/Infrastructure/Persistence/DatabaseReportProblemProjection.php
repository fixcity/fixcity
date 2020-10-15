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

use FixCity\Component\CQRS\Projection;
use FixCity\Component\Database\DatabaseAdapter;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Component\Mapping\ProblemFields;

class DatabaseReportProblemProjection implements Projection
{
    private DatabaseAdapter $database;

    public function __construct(DatabaseAdapter $database)
    {
        $this->database = $database;
    }

    public function __invoke(PublicEvent $event): void
    {
        $this->database->insert([
            'problem_id' => $event->payload()[ProblemFields::PROBLEM_ID],
        ]);
    }
}
