<?php

namespace Fulll\App\Fleet\CommandHandler;

use Fulll\Infra\Database;
use Fulll\Infra\Fleet\Exception\FleetNotFoundException;
use Fulll\Infra\Fleet\Exception\VehicleNotFoundException;
use Fulll\Infra\Fleet\Repository\FleetRepository;
use Fulll\Infra\Fleet\Repository\VehicleRepository;

class LocalizeVehicleCommandHandler
{
    public function handle(int $fleetId, string $vehiclePlateNumber, string $lat, string $lng): array
    {
        try {
            $connection = (new Database())->login();

            $fleetRepository = new FleetRepository($connection);
            $fleet = $fleetRepository->findById($fleetId);

            if ($fleet === null) {
                throw new FleetNotFoundException();
            }

            $vehicleRepository = new VehicleRepository($connection);
            $vehicle = $vehicleRepository->findByFleetIdAndPlateNumber($fleetId, $vehiclePlateNumber);

            if ($vehicle === null) {
                throw new VehicleNotFoundException();
            }

            if (((string) $vehicle->getLat() === $lat) && ((string) $vehicle->getLng() === $lng)) {
                return [
                    'message' => 'This vehicle is already parked at this location.'
                ];
            }

            $vehicle->setLat($lat)
                    ->setLng($lng)
            ;

            $vehicleRepository->save($vehicle);


            return [
                'message' => 'This vehicle has been moved to the provided location.',
                'fleetId' => $fleet->getId(),
            ];
        } catch (\Throwable $e) {
            // Watch on Throwable to also catch any Error object that could be thrown
            return [
                'message' => $e->getMessage(),
                'fleetId' => null
            ];
        }
    }
}