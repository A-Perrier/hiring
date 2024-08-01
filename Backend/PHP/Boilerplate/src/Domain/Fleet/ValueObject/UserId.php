<?php

namespace Fulll\Domain\Fleet\ValueObject;

class UserId
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