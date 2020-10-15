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

namespace FixCity\Platform\Domain\Model\Problem;

use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Model\Collaborator\Author;

final class Comment
{
    private Uuid $commentId;

    private Uuid $problemId;

    private Author $author;

    private string $description;

    public function __construct(Uuid $commentId, Uuid $problemId, Author $author, string $description)
    {
        $this->commentId   = $commentId;
        $this->problemId   = $problemId;
        $this->author      = $author;
        $this->description = $description;
    }

    public function commentId(): Uuid
    {
        return $this->commentId;
    }

    public function problemId(): Uuid
    {
        return $this->problemId;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function description(): string
    {
        return $this->description;
    }
}
