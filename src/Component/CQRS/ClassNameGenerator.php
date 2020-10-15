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

final class ClassNameGenerator
{
    private const COMMAND_NAMESPACE = 'FixCity\Platform\Application\Command\\';

    private const EVENT_NAMESPACE = 'FixCity\Platform\Domain\Event\\';

    private string $className;

    private string $prefixOrSuffix;

    public function __construct(string $className, string $prefixOrSuffix = 'Problem')
    {
        $this->className      = $className;
        $this->prefixOrSuffix = $prefixOrSuffix;
    }

    public function commandClassName(): string
    {
        return self::COMMAND_NAMESPACE . ucfirst($this->className) . $this->prefixOrSuffix;
    }

    public function eventClassName(): string
    {
        return self::EVENT_NAMESPACE . $this->prefixOrSuffix . ucfirst($this->className) . 'ed';
    }

    public function handlerClassName(): string
    {
        return self::COMMAND_NAMESPACE . ucfirst($this->className) . $this->prefixOrSuffix . 'Handler';
    }
}
