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

use FixCity\Component\Identity\IncrementalId;

final class Type
{
    private IncrementalId $typeId;

    private string $type;

    public function __construct(IncrementalId $typeId, string $type)
    {
        $this->typeId = $typeId;
        $this->type   = $type;
    }

    public function typeId(): IncrementalId
    {
        return $this->typeId;
    }

    public function type(): string
    {
        return $this->type;
    }
}
