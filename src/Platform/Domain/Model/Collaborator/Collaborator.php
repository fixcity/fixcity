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

namespace FixCity\Platform\Domain\Model\Collaborator;

class Collaborator
{
    private string $identity;

    private string $name;

    private string $emailAddress;

    public function __construct(string $identity, string $name, string $emailAddress)
    {
        $this->identity     = $identity;
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }
}
