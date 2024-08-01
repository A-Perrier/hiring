<?php

namespace Fulll\Domain\Fleet\ValueObject;

class FleetId
{
    public function __construct(
        private string $value
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}