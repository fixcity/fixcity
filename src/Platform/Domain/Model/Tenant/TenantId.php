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

namespace FixCity\Platform\Domain\Model\Tenant;

use Assert\AssertionFailedException;
use FixCity\Component\Assert\Assertion;

final class TenantId
{
    private string $id;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $id)
    {
        Assertion::length($id, 36, 'Tenant id length is invalid');
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
