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

namespace FixCity\Component\Assert;

use Assert\Assertion as BaseAssertion;
use FixCity\Component\Assert\Exception\InvalidAssertionException;

final class Assertion extends BaseAssertion
{
    protected static $exceptionClass = InvalidAssertionException::class;
}
