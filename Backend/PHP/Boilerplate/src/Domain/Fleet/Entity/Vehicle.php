<?php

namespace Fulll\Domain\Fleet\Entity;

use Fulll\Domain\Fleet\Exception\VehicleAlreadyParkedAtLocationException;
use Fulll\Domain\Fleet\ValueObject\Location;
use Fulll\Domain\Fleet\ValueObject\VehiclePlateNumber;

class Vehicle
{
    public function __construct(
        private VehiclePlateNumber $plateNumber,
        private ?Location $location = null,
    ) {
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber->getValue();
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        if (
            $location->getLat() === $this->location?->getLat() &&
            $location->getLng() === $this->location?->getLng()
        ) {
            throw new VehicleAlreadyParkedAtLocationException(vehiclePlateNumber: $this->getPlateNumber());
        }

        $this->location = $location;
        return $this;
    }
}