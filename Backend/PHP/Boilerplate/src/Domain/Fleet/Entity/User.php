<?php

namespace Fulll\Domain\Fleet\Entity;

use Fulll\Domain\Fleet\ValueObject\UserId;

class User
{
    private UserId $id;
    private Fleet $fleet;
    public function __construct(string $uniqId)
    {
        $this->id = new UserId("$uniqId");
        $this->fleet = new Fleet("fleet_$uniqId");
    }

    public function getFleet(): ?Fleet
    {
        return $this->fleet;
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }
}