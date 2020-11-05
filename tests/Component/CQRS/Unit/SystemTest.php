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
use FixCity\Component\CQRS\Handler;
use FixCity\Component\CQRS\QueryBus;
use FixCity\Component\CQRS\System;
use FixCity\Component\EventStore\EventStream\EventStream;
use FixCity\Component\EventStore\EventStream\PrivateEvent;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Application\Double\Dummy\DummyCommand;
use FixCity\Platform\Application\Double\Dummy\DummyCommandHandler;
use PHPUnit\Framework\TestCase;

class SystemTest extends TestCase
{
    public function test_flushing_event_stream_after_handing_command() : void
    {
        $eventStream = $this->createMock(EventStream::class);

        $eventStream->expects($this->once())
            ->method('record');

        $eventStream->expects($this->once())
            ->method('flush');

        $system = new System(
            new CommandBus(
                new DummyTransactionManager(),
                [
                    new class($eventStream) implements Handler {
                        private EventStream $eventStream;

                        public function __construct(EventStream $eventStream)
                        {
                            $this->eventStream = $eventStream;
                        }

                        public function __toString(): string
                        {
                            return DummyCommandHandler::class;
                        }

                        public function __invoke(): void
                        {
                            $this->eventStream->record(
                                new PrivateEvent(Uuid::generate(), [], new \DateTimeImmutable('now'))
                            );
                        }
                    },
                ]
            ),
            new QueryBus(),
            $eventStream
        );

        $system->handle(new DummyCommand());
    }

    public function test_failed_event_stream_flush_after_handing_command() : void
    {
        $eventStream = $this->createMock(EventStream::class);

        $eventStream->expects($this->once())
            ->method('record');

        $eventStream->method('flush')
            ->willThrowException(new \RuntimeException('Can\'t Flush'));

        $system = new System(
            new CommandBus(
                new DummyTransactionManager(),
                [
                    new class($eventStream) implements Handler {
                        private EventStream $eventStream;

                        public function __construct(EventStream $eventStream)
                        {
                            $this->eventStream = $eventStream;
                        }

                        public function __toString(): string
                        {
                            return DummyCommandHandler::class;
                        }

                        public function __invoke(): void
                        {
                            $this->eventStream->record(
                                new PrivateEvent(Uuid::generate(), [], new \DateTimeImmutable('now'))
                            );
                        }
                    },
                ]
            ),
            new QueryBus(),
            $eventStream
        );

        $this->expectException(\Exception::class);

        $system->handle(new DummyCommand());
    }
}
