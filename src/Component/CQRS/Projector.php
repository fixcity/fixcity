<?php
/**
 * This file is part of the fixcity package.
 *
 * (c) FixCity <fixcity.org@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @noinspection SelfClassReferencingInspection */

declare(strict_types=1);

namespace FixCity\Component\CQRS;

use FixCity\Component\Database\DatabaseAdapter;
use FixCity\Component\EventBus\EventBus;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Component\EventBus\Subscriber;

abstract class Projector
{
    protected EventBus $eventBus;

    protected string $topic;

    protected DatabaseAdapter $database;

    public function __construct(string $topic, EventBus $eventBus, DatabaseAdapter $database)
    {
        $this->topic    = $topic;
        $this->eventBus = $eventBus;
        $this->database = $database;
    }

    public function registerToTopic(): void
    {
        $this->eventBus->registerTo($this->topic, new class($this) implements Subscriber {
            private Projector $projector;

            public function __construct(Projector $projector)
            {
                $this->projector = $projector;
            }

            public function receive(PublicEvent $event): void
            {
                $this->projector->projectWhen($event);
            }
        });
    }

    abstract public function projectWhen(PublicEvent $event): void;
}
