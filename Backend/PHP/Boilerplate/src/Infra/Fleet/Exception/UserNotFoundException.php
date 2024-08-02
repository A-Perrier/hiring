<?php

namespace Fulll\Infra\Fleet\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct(
        string $message = 'The user was not found.'
    ) {
        parent::__construct($message);
    }
}