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

namespace FixCity\Component\CQRS;

use Assert\AssertionFailedException;
use FixCity\Component\Assert\Assertion;
use FixCity\Component\CQRS\Exception\InvalidArgumentException;

final class CommandBus
{
    /**
     * @var Handler[]
     */
    private array $handlers;

    private TransactionManager $transactionManager;

    /**
     * @param TransactionManager $transactionManager
     * @param Handler[]          $handlers
     *
     * @throws AssertionFailedException
     */
    public function __construct(TransactionManager $transactionManager, array $handlers = [])
    {
        $this->handlers = [];
        foreach ($handlers as $handler) {
            Assertion::methodExists('__invoke', $handler, 'Can\'t register command handler without __invoke method.');
            $this->handlers[(string)$handler] = $handler;
        }
        $this->transactionManager = $transactionManager;
    }

    /**
     * @throws \Throwable
     * @throws \RuntimeException
     */
    public function handle(Command $command): void
    {
        $commandName = (string)$command;
        if (array_key_exists($commandName . 'Handler', $this->handlers)) {
            $this->transactionManager->begin();

            try {
                /** @var callable $handler */
                $handler = $this->handlers[$commandName . 'Handler'];
                $handler($command);
                $this->transactionManager->commit();
            } catch (\Throwable $exception) {
                $this->transactionManager->rollback();

                throw $exception;
            }
        } else {
            throw InvalidArgumentException::unknownCommand($commandName);
        }
    }
}
