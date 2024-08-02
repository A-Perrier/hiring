<?php

namespace Fulll\Infra\Fleet\Repository;

use Fulll\Domain\Fleet\Entity\Fleet;
use Fulll\Domain\Fleet\Entity\Vehicle;
use Fulll\Infra\AbstractRepository;
use PDO;

class FleetRepository extends AbstractRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Fleet
    {
        $sql = "SELECT * FROM `fleets` WHERE `id` = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Fleet::class);
        $row = $stmt->fetch();

        return false === $row ? null : $row;
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `fleets` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
        )";

        $this->query($sql);
    }

    public function create(): Fleet
    {
        $sql = "INSERT INTO `fleets` (`id`) VALUES (NULL)";
        $this->query($sql);
        return $this->findById($this->pdo->lastInsertId());
    }

    public function addVehicle(Fleet $fleet, Vehicle $vehicle): void
    {
        $sql = "INSERT INTO `fleets_vehicles` (`id`, `fleetId`, `vehicleId`) VALUES (NULL, :fleetId, :vehicleId)";
        $this->query($sql, ['fleetId' => $fleet->getId(), 'vehicleId' => $vehicle->getId()]);
    }

    public function hasVehicle(Fleet $fleet, Vehicle $vehicle): bool
    {
        $sql = "SELECT `id` FROM `fleets_vehicles` WHERE `fleetId` = :fleetId AND `vehicleId` = :vehicleId";
        $stmt = $this->query($sql, ['fleetId' => $fleet->getId(), 'vehicleId' => $vehicle->getId()]);
        return is_array($stmt->fetch());
    }
}