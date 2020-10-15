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

namespace FixCity\Platform\Domain;

use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Model\Problem\Type;

interface Types
{
    public function getById(Uuid $typeId): Type;

    public function add(Uuid $typeId, Type $type): void;

    public function nextIdentity(): Uuid;
}
