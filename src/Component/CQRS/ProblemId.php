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

trait ProblemId
{
    use ClassName;

    private string $problemId;

    public function __construct(string $problemId)
    {
        $this->problemId = $problemId;
    }

    public function problemId(): string
    {
        return $this->problemId;
    }
}
