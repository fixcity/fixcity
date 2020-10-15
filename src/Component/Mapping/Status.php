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

namespace FixCity\Component\Mapping;

final class Status
{
    public const REPORT_PROBLEM = 'report';

    public const OPEN_STATUS = 'open';

    public const IN_PROGRESS_STATUS = 'inProgress';

    public const CLOSE_STATUS = 'close';

    public const FIX_STATUS = 'fix';
}
