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

use FixCity\Component\EventBus\PublicEvent;

interface Projection
{
    public const PROBLEMS_TABLE = 'fixcity_problems';

    public function __invoke(PublicEvent $event): void;
}
