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

final class Location
{
    private string $countryCode;

    private string $city;

    private string $address;

    private float $lat;

    private float $lng;

    public function __construct(string $countryCode, string $city, string $address, float $lat, float $lng)
    {
        $this->countryCode = $countryCode;
        $this->city        = $city;
        $this->address     = $address;
        $this->lat         = $lat;
        $this->lng         = $lng;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function lat(): float
    {
        return $this->lat;
    }

    public function lng(): float
    {
        return $this->lng;
    }
}
