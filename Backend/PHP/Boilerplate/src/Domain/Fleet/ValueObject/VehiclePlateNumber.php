<?php

namespace Fulll\Domain\Fleet\ValueObject;

class VehiclePlateNumber
{
    public function __construct(
        private string $plateNumber,
    ) {
    }

    public function getValue(): string
    {
        return $this->plateNumber;
    }
}