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

namespace FixCity\Component\Identity;

use Assert\AssertionFailedException;
use FixCity\Component\Assert\Assertion;

final class IncrementalId
{
    private int $id;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(int $id)
    {
        Assertion::greaterThan($id, 0);
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }
}
