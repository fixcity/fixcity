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

namespace FixCity\Component\CQRS\Exception;

final class InvalidArgumentException extends \InvalidArgumentException
{
    private function __construct(string $message = '')
    {
        parent::__construct($message);
    }

    public static function aggregateNotExists(string $aggregateId): self
    {
        return new self(sprintf('Aggregate with id: "%s" not exists', $aggregateId));
    }

    public static function aggregateIsInvalid(): self
    {
        return new self('Aggregate is invalid');
    }

    public static function unknownEvent(string $eventName): self
    {
        return new self(sprintf('Unknown event "%s"', $eventName));
    }

    public static function unknownCommand(string $commandName): self
    {
        return new self(sprintf('Unknown command "%s"', $commandName));
    }

    public static function unknownProjectionForEvent(string $eventName): self
    {
        return new self(sprintf('Unknown projection for event "%s"', $eventName));
    }

    public static function usingGenericQuery(): self
    {
        return new self('Using generic Query interface in order to access specific query is impossible.');
    }

    /**
     * @param string $queryName
     * @param string[]  $queries
     *
     * @return self
     */
    public static function queryNotExists(string $queryName, array $queries): self
    {
        return new self(sprintf(
            'Query "\%s" does not exists. Available Query: "\%s"',
            $queryName,
            implode('", "', $queries)
        ));
    }

    public static function queryAlreadyRegistered(string $queryRegisteredName, string $queryName): self
    {
        return new self(sprintf(
            'Query %s that implements same interfaces as %s is already registered.',
            $queryRegisteredName,
            $queryName
        ));
    }
}
