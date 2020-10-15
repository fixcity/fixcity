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

namespace FixCity\Platform\Infrastructure\Persistence;

use Assert\AssertionFailedException;
use FixCity\Component\Database\DatabaseAdapter;
use FixCity\Component\Identity\IncrementalId;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Model\Problem\Type;
use FixCity\Platform\Domain\Types;

final class DatabaseTypes implements Types
{
    private const TYPES_TABLE = 'fixcity_types';

    private DatabaseAdapter $database;

    public function __construct(DatabaseAdapter $database)
    {
        $this->database = $database;
    }

    /**
     * @throws AssertionFailedException
     */
    public function getById(Uuid $typeId): Type
    {
        $payload = $this->database->fetchAssoc(
            'SELECT * FROM `' . self::TYPES_TABLE . '` WHERE aggregate_id = ?',
            [(string)$typeId]
        );

        return new Type(new IncrementalId($payload['type_id']), $payload['type']);
    }

    public function add(Uuid $typeId, Type $type): void
    {
        $this->database->insert([
            'aggregate_id' => (string)$typeId,
            'type' => $type->type(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function nextIdentity(): Uuid
    {
        return Uuid::generate();
    }
}
