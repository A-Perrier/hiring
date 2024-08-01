<?php

namespace Fulll\Domain\Fleet\ValueObject;

class Location
{
    public function __construct(
        private string $lat,
        private string $lng,
    ) {
    }

    public function getLat(): string
    {
        return $this->lat;
    }

    public function getLng(): string
    {
        return $this->lng;
    }
}