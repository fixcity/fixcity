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

namespace FixCity\Component\CQRS\Double\Dummy;

use FixCity\Component\CQRS\TransactionManager;

final class DummyTransactionManager implements TransactionManager
{
    public function begin(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollback(): void
    {
    }
}
