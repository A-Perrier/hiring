<?php

namespace Fulll\Domain\Fleet\Entity;

class Vehicle
{
    private int $id;
    private string $plateNumber;
    private ?string $lat = null;
    private ?string $lng = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function setPlateNumber(string $plateNumber): self
    {
        $this->plateNumber = $plateNumber;
        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): self
    {
        $this->lng = $lng;
        return $this;
    }
}