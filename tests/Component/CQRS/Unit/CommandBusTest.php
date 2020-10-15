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

use FixCity\Component\CQRS\Command;
use FixCity\Component\CQRS\CommandBus;
use FixCity\Component\CQRS\Double\Dummy\DummyTransactionManager;
use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\CQRS\Handler;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    public function test_registering_command_without_invoke_method(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t register command handler without __invoke method.');

        new CommandBus(
            new DummyTransactionManager(),
            [
                new class implements Handler {
                    public function __toString(): string
                    {
                        return 'nothing';
                    }
                },
            ]
        );
    }

    public function test_handling_unknown_command(): void
    {
        $commandBus = new CommandBus(
            new DummyTransactionManager(),
            [
                new class implements Handler {
                    public function __invoke(): void
                    {
                    }

                    public function __toString(): string
                    {
                        return 'nothing';
                    }
                },
            ]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown command "FancyClassName"');

        $commandBus->handle(new class implements Command {
            public function __toString(): string
            {
                return 'FancyClassName';
            }
        });
    }
}
