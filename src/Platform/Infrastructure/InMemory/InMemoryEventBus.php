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

final class InMemoryEventBus implements EventBus
{
    /**
     * @var array<string,array<Subscriber>>
     */
    private array $topics;

    public function __construct()
    {
        $this->topics = [];
    }

    public function publishTo(string $topic, PublicEvent $event): void
    {
        if (\array_key_exists($topic, $this->topics)) {
            foreach ($this->topics[$topic] as $index => $subscriber) {
                $subscriber->receive($event);
                unset($this->topics[$topic][$index]);
            }
        }
    }

    public function registerTo(string $topic, Subscriber $subscriber): void
    {
        if (!\array_key_exists($topic, $this->topics)) {
            $this->topics[$topic] = [];
        }

        $this->topics[$topic][] = $subscriber;
    }
}
