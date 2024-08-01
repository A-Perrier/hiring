<?php

namespace Fulll\Domain\Fleet\Entity;

use Fulll\Domain\Fleet\Exception\VehicleAlreadyRegisteredException;
use Fulll\Domain\Fleet\ValueObject\FleetId;
use Fulll\Domain\Fleet\ValueObject\Location;

class Fleet
{
    private FleetId $fleetId;
    /** @var Vehicle[] */
    private array $vehicles = [];

    public function __construct(string $uniqId)
    {
        $this->fleetId = new FleetId($uniqId);
    }

    public function getFleetId(): FleetId
    {
        return $this->fleetId;
    }

    /**
     * @return Vehicle[]
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        foreach ($this->vehicles as $fleetVehicle) {
            if ($fleetVehicle->getPlateNumber() === $vehicle->getPlateNumber()) {
                throw new VehicleAlreadyRegisteredException(vehiclePlateNumber: $vehicle->getPlateNumber());
            }
        }

        $this->vehicles[] = $vehicle;
        return $this;
    }

    public function localizeVehicle(Location $location): ?Vehicle
    {
        foreach ($this->vehicles as $fleetVehicle) {
            if (
                ($fleetVehicle->getLocation()?->getLat() === $location->getLat()) &&
                ($fleetVehicle->getLocation()?->getLng() === $location->getLng())
            ) {
                return $fleetVehicle;
            }
        }

        return null;
    }
}