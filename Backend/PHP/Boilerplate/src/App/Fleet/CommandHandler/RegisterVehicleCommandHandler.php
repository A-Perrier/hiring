<?php

namespace Fulll\App\Fleet\CommandHandler;

use Fulll\App\Fleet\Command\RegisterVehicleCommand;

class RegisterVehicleCommandHandler
{
    public function handle(RegisterVehicleCommand $command): void
    {
        $fleet = $command->getUser()->getFleet();
        $fleet->addVehicle($command->getVehicle());

        // Émettre un événement VehicleRegistered
    }
}