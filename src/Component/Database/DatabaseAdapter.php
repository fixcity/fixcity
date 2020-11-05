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

namespace FixCity\Component\Database;

interface DatabaseAdapter
{
    public function count(string $identifier): int;

    /**
     * @param array<mixed> $data
     */
    public function insert(array $data): void;

    /**
     * @param array<mixed> $data
     * @param array<mixed> $identifier
     */
    public function update(array $data = [], array $identifier = []): void;

    /**
     * @param string               $statement
     * @param array<mixed> $params
     *
     * @return array<mixed>
     */
    public function fetchAssoc(string $statement, array $params = []): array;

    /**
     * @param string               $statement
     * @param array<mixed> $params
     *
     * @return array<mixed>
     */
    public function fetchAll(string $statement, array $params = []): array;
}
