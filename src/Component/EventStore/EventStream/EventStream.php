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

namespace FixCity\Component\EventStore\EventStream;

/**
 * @extends \IteratorAggregate<int, Event>
 */
interface EventStream extends \IteratorAggregate
{
    public function record(Event $event): void;

    /**
     * @param array<int, Event> $events
     */
    public function recordAll(array $events): void;

    public function flush(): void;
}
