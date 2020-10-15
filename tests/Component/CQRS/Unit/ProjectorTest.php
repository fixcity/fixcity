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

namespace FixCity\Component\CQRS\Unit;

use FixCity\Component\CQRS\CommandBus;
use FixCity\Component\CQRS\Double\Dummy\DummyTransactionManager;
use FixCity\Component\CQRS\Double\Stub\EventStreamStub;
use FixCity\Component\CQRS\Projector;
use FixCity\Component\CQRS\QueryBus;
use FixCity\Component\CQRS\System;
use FixCity\Component\EventBus\PublicEvent;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Application\Double\Dummy\DummyCommand;
use FixCity\Platform\Application\Double\Dummy\DummyCommandHandler;
use PHPUnit\Framework\TestCase;

class ProjectorTest extends TestCase
{
    public function test_register_to_topic(): void
    {
        $projector = $this->createMock(Projector::class);

        $projector->expects($this->once())
            ->method('registerToTopic');

        $system = new System(
            new CommandBus(new DummyTransactionManager(), [
                new DummyCommandHandler(),
            ]),
            new QueryBus(),
            new EventStreamStub(),
            $projector
        );

        $system->handle(new DummyCommand());
    }

    public function test_project(): void
    {
        $projector = $this->createMock(Projector::class);

        $projector->expects($this->once())
            ->method('registerToTopic')
            ->willReturnCallback(
                static function () use ($projector) {
                    $projector->projectWhen(
                        new PublicEvent(Uuid::generate(), 'SampleEvent', [], new \DateTimeImmutable('now'))
                    );
                }
            );

        $projector->expects($this->once())
            ->method('projectWhen');

        $system = new System(
            new CommandBus(new DummyTransactionManager(), [
                new DummyCommandHandler(),
            ]),
            new QueryBus(),
            new EventStreamStub(),
            $projector
        );

        $system->handle(new DummyCommand());
    }
}
