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

namespace FixCity\Platform\Infrastructure\Persistence\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Exception;
use FixCity\Component\Database\DatabaseAdapter;

final class DbalDatabaseAdapter implements DatabaseAdapter
{
    private Connection $database;

    private string $tableName;

    public function __construct(Connection $database, string $tableName)
    {
        $this->database  = $database;
        $this->tableName = $tableName;
    }

    /**
     * @throws Exception
     * @throws DriverException
     */
    public function count(string $identifier): int
    {
        /** @var Result $query */
        $query = $this->database->executeQuery(
            sprintf('SELECT count(*) FROM `%s` WHERE aggregate_id = ?', $this->tableName),
            [$identifier]
        );

        return (int)$query->fetchOne();
    }

    /**
     * @param array<mixed> $data
     *
     * @throws Exception
     */
    public function insert(array $data = []): void
    {
        $this->database->insert($this->tableName, $data);
    }

    /**
     * @throws Exception
     */
    public function update(array $data = [], array $identifier = []): void
    {
        $this->database->update($this->tableName, $data, $identifier);
    }

    /**
     * @throws Exception
     */
    public function fetchAssoc(string $statement, array $params = []): array
    {
        $result = $this->database->fetchAssociative($statement, $params);
        if ($result === false) {
            return [];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function fetchAll(string $statement, array $params = []): array
    {
        return $this->database->fetchAllAssociative($statement, $params);
    }
}
