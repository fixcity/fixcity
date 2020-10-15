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

namespace FixCity\Platform\Infrastructure\InMemory;

use FixCity\Component\EventBus\EventBus;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Component\EventBus\Subscriber;

final class InMemoryDummyEventBus implements EventBus
{
    public function publishTo(string $topic, PublicEvent $event): void
    {
    }

    public function registerTo(string $topic, Subscriber $subscriber): void
    {
    }
}
