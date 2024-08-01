<?php

namespace Fulll\Domain\Fleet\Exception;

class VehicleAlreadyRegisteredException extends \Exception
{
    public function __construct(
        string $message = 'The vehicle %s has already been registered in your fleet.',
        ?string $vehiclePlateNumber = null,
    ) {
        parent::__construct(sprintf($message, $vehiclePlateNumber));
    }
}