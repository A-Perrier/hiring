<?php

namespace Fulll\Domain\Fleet\Exception;

class VehicleNotRegisteredInFleetException extends \Exception
{
    public function __construct(
        string $message = 'The vehicle %s isn\'t part of your fleet.',
        ?string $vehiclePlateNumber = null,
    ) {
        parent::__construct(sprintf($message, $vehiclePlateNumber));
    }
}