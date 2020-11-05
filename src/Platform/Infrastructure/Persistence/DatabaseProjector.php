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

use FixCity\Component\CQRS\Projector;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Platform\Infrastructure\Persistence\Factory\ProjectionFactory;

final class DatabaseProjector extends Projector
{
    public function projectWhen(PublicEvent $event): void
    {
        $projection = ProjectionFactory::createProjection($event->eventName(), $this->database);
        $projection($event);
    }
}
