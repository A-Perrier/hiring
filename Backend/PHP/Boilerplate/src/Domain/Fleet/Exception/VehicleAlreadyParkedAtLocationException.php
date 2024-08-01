<?php

namespace Fulll\Domain\Fleet\Exception;

class VehicleAlreadyParkedAtLocationException extends \Exception
{
    public function __construct(
        string $message = 'The vehicle %s has already been parked at this location.',
        ?string $vehiclePlateNumber = null,
    ) {
        parent::__construct(sprintf($message, $vehiclePlateNumber));
    }
}