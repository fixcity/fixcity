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

namespace FixCity\Platform\Domain\Model\Problem;

final class Description
{
    private string $description;

    /**
     * @var array<int, Attachment>
     */
    private array $attachments;

    /**
     * Description constructor.
     *
     * @param string                 $description
     * @param array<int, Attachment> $attachments
     */
    public function __construct(string $description = '', array $attachments = [])
    {
        $this->description = $description;
        $this->attachments = $attachments;
    }

    public function addAttachment(Attachment $attachment): void
    {
        $this->attachments[] = $attachment;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return $this->attachments;
    }
}
