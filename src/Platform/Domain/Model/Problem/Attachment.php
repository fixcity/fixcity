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

final class Attachment
{
    private Uuid $attachmentId;

    private Uuid $problemId;

    private string $path;

    public function __construct(Uuid $attachmentId, Uuid $problemId, string $path)
    {
        $this->attachmentId = $attachmentId;
        $this->problemId    = $problemId;
        $this->path         = $path;
    }

    public function attachmentId(): Uuid
    {
        return $this->attachmentId;
    }

    public function problemId(): Uuid
    {
        return $this->problemId;
    }

    public function path(): string
    {
        return $this->path;
    }
}
