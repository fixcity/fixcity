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

use FixCity\Component\CQRS\Command;
use FixCity\Component\CQRS\ProblemId;

final class FixProblem implements Command
{
    use ProblemId;
}
