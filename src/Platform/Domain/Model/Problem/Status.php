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

final class Status
{
    public const REPORTED = 'reported';

    public const OPENED = 'open';

    public const IN_PROGRESS = 'in_progress';

    public const FIXED = 'fixed';

    public const CLOSED = 'closed';
}
