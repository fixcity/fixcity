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

namespace FixCity\Component\CQRS;

use FixCity\Component\Mapping\Status;

final class MethodNameGenerator
{
    private string $methodName;

    private string $suffix;

    public function __construct(string $methodName, string $suffix = 'ed')
    {
        $this->methodName = $methodName;
        $this->suffix     = $suffix;
    }

    public function methodName(): string
    {
        return $this->methodName . ($this->methodName === Status::IN_PROGRESS_STATUS ? '' : $this->suffix);
    }
}
