<?php

namespace Fulll\Infra\Fleet\Exception;

class VehicleNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'The vehicle was not found in your fleet.'
    ) {
        parent::__construct($message);
    }
}