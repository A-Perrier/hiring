<?php

namespace Fulll\Infra\Fleet\Exception;

class FleetNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'The fleet was not found.'
    ) {
        parent::__construct($message);
    }
}