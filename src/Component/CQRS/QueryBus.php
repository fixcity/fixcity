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

use FixCity\Component\CQRS\Exception\InvalidArgumentException;

final class QueryBus
{
    /**
     * @var Query[]
     */
    private array $queries;

    /**
     * @param Query[] $queries
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $queries = [])
    {
        $this->queries = [];
        foreach ($queries as $query) {
            $this->register($query);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function handle(string $queryName): Query
    {
        if ($queryName === Query::class) {
            throw InvalidArgumentException::usingGenericQuery();
        }

        if (!array_key_exists($queryName, $this->queries)) {
            throw InvalidArgumentException::queryNotExists($queryName, $this->availableQueries());
        }

        return $this->queries[$queryName];
    }

    /**
     * @return string[]
     */
    public function availableQueries(): array
    {
        return array_keys($this->queries);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function register(Query $query): void
    {
        if (empty($this->queries)) {
            $this->queries[(string)$query] = $query;

            return;
        }

        foreach ($this->queries as $queryRegistered) {
            if (\class_implements($queryRegistered) === \class_implements($query)) {
                throw InvalidArgumentException::queryAlreadyRegistered(
                    \get_class($queryRegistered),
                    \get_class($query)
                );
            }
        }

        $this->queries[(string)$query] = $query;
    }
}
