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

use Doctrine\DBAL\Driver\Connection;
use FixCity\Component\CQRS\TransactionManager;

final class DbalTransactionManager implements TransactionManager
{
    private Connection $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function begin(): void
    {
        $this->database->beginTransaction();
    }

    public function commit(): void
    {
        $this->database->commit();
    }

    public function rollback(): void
    {
        $this->database->rollBack();
    }
}
