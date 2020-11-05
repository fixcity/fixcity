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

namespace FixCity\Platform\Application\Command;

use DateTimeImmutable;
use FixCity\Component\CQRS\ClassName;
use FixCity\Component\CQRS\Command;
use FixCity\Platform\Domain\Model\Collaborator\Author;
use FixCity\Platform\Domain\Model\Collaborator\Organization;
use FixCity\Platform\Domain\Model\Problem\Description;
use FixCity\Platform\Domain\Model\Problem\Location;
use FixCity\Platform\Domain\Model\Problem\Type;

final class ReportProblem implements Command
{
    use ClassName;

    private string $problemId;

    private int $numberId;

    private Type $type;

    private Location $location;

    private Description $description;

    private string $tenantId;

    private Author $author;

    private Organization $organization;

    private DateTimeImmutable $createdAt;

    public function __construct(
        string $problemId,
        int $numberId,
        Type $type,
        Location $location,
        Description $description,
        string $tenantId,
        Author $author,
        Organization $organization,
        DateTimeImmutable $createdAt
    ) {
        $this->problemId    = $problemId;
        $this->numberId     = $numberId;
        $this->type         = $type;
        $this->location     = $location;
        $this->description  = $description;
        $this->tenantId     = $tenantId;
        $this->author       = $author;
        $this->organization = $organization;
        $this->createdAt    = $createdAt;
    }

    public function problemId(): string
    {
        return $this->problemId;
    }

    public function numberId(): int
    {
        return $this->numberId;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function location(): Location
    {
        return $this->location;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function tenantId(): string
    {
        return $this->tenantId;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
