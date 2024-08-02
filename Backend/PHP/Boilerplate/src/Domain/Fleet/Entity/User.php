<?php

namespace Fulll\Domain\Fleet\Entity;

class User
{
    private int $id;
    private ?int $fleetId = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getFleetId(): ?int
    {
        return $this->fleetId;
    }

    public function setFleetId(?int $fleetId): self
    {
        $this->fleetId = $fleetId;
        return $this;
    }
}