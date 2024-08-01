<?php

namespace Fulll\Domain\Fleet\Exception;

class VehicleNotFoundAtLocationException extends \Exception
{
    public function __construct(
        string $message = 'No vehicle was found at this location.'
    ) {
        parent::__construct($message);
    }
}