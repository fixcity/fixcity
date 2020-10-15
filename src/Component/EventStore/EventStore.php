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

namespace FixCity\Component\EventStore;

use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\Identity\Uuid;

interface EventStore
{
    public function loadEventStream(Uuid $aggregateId): EventStream;

    public function store(Uuid $aggregateId, EventStream $events): void;
}
