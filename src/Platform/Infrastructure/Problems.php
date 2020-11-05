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

namespace FixCity\Platform\Infrastructure;

use FixCity\Component\CQRS\Command;
use FixCity\Component\CQRS\System;
use FixCity\Component\Identity\Uuid;
use FixCity\Platform\Domain\Problems as DatabaseProblems;

final class Problems
{
    private System $system;

    private DatabaseProblems $problems;

    public function __construct(System $system, DatabaseProblems $problems)
    {
        $this->system   = $system;
        $this->problems = $problems;
    }

    /**
     * @throws \Throwable
     */
    public function handle(Command $command): void
    {
        $this->system->handle($command);
    }

    public function nextIdentity(): Uuid
    {
        return $this->problems->nextIdentity();
    }
}
