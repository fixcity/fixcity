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

namespace FixCity\Platform\Application\Double\Dummy;

use FixCity\Component\CQRS\ClassName;
use FixCity\Component\CQRS\Handler;

final class DummyCommandHandler implements Handler
{
    use ClassName;

    public function __invoke(): void
    {
    }
}
