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

use FixCity\Component\CQRS\Exception\InvalidArgumentException;
use FixCity\Component\CQRS\Query;
use FixCity\Component\CQRS\QueryBus;
use FixCity\Platform\Application\Double\Dummy\DummyQuery;
use FixCity\Platform\Application\Double\Fake\FakeQuery;
use PHPUnit\Framework\TestCase;

class QueryBusTest extends TestCase
{
    public function test_accessing_queries(): void
    {
        $queryBus = new QueryBus([new DummyQuery()]);

        $this->assertInstanceOf(
            DummyQuery::class,
            $queryBus->handle(DummyQuery::class)
        );
    }

    public function test_attempt_to_access_query_by_generic_query_interface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Using generic Query interface in order to access specific query is impossible.');

        $queryBus = new QueryBus();
        $queryBus->handle(Query::class);
    }

    public function test_accessing_not_registered_query(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Query "\%s" does not exists. Available Query: "\%s"',
                \DateTime::class,
                DummyQuery::class
            )
        );

        $queryBus = new QueryBus([new DummyQuery()]);
        $queryBus->handle(\DateTime::class);
    }

    public function test_registering_two_implementations_of_the_same_query(): void
    {
        $firstQuery = new class implements FakeQuery {
            public function __toString(): string
            {
                return DummyQuery::class;
            }
        };
        $secondQuery = new class implements FakeQuery {
            public function __toString(): string
            {
                return DummyQuery::class;
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Query %s that implements same interfaces as %s is already registered.',
                get_class($firstQuery),
                get_class($secondQuery)
            )
        );

        new QueryBus([$firstQuery, $secondQuery]);
    }
}
