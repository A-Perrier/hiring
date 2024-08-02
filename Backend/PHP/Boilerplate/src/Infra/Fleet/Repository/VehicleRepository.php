<?php

namespace Fulll\Infra\Fleet\Repository;

use Fulll\Domain\Fleet\Entity\Fleet;
use Fulll\Domain\Fleet\Entity\Vehicle;
use Fulll\Infra\AbstractRepository;
use PDO;

class VehicleRepository extends AbstractRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `vehicles` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `plateNumber` varchar(50) NOT NULL,
            `lat` varchar(50) NULL,
            `lng` varchar(50) NULL
        )";

        $this->query($sql);
    }

    public function save(Vehicle $vehicle): ?Vehicle
    {
        $sql = "UPDATE `vehicles` SET `plateNumber` = :plateNumber, `lat` = :lat, `lng` = :lng WHERE `id` = :id";
        $stmt = $this->query(
            $sql,
            [
                'plateNumber' => $vehicle->getPlateNumber(),
                'lat' => $vehicle->getLat(),
                'lng' => $vehicle->getLng(),
                'id' => $vehicle->getId()
            ]
        );

        return $this->findByPlateNumber($vehicle->getPlateNumber());
    }

    public function create(Vehicle $vehicle): Vehicle
    {
        $sql = "INSERT INTO `vehicles` (`id`, `plateNumber`, `lat`, `lng`) VALUES (NULL, :plateNumber, :lat, :lng)";
        $this->query($sql, [
            'plateNumber' => $vehicle->getPlateNumber(),
            'lat' => $vehicle->getLat(),
            'lng' => $vehicle->getLng()
        ]);

        return $this->findByPlateNumber($vehicle->getPlateNumber());
    }

    public function findByPlateNumber(string $plateNumber): ?Vehicle
    {
        $sql = "SELECT * FROM `vehicles` WHERE `plateNumber` = :plateNumber";
        $stmt = $this->query($sql, ['plateNumber' => $plateNumber]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Vehicle::class);
        $row = $stmt->fetch();

        return false === $row ? null : $row;
    }

    public function findByFleetIdAndPlateNumber(int $fleetId, string $plateNumber): ?Vehicle
    {
        $sql = "SELECT v.* FROM `vehicles` v
                LEFT JOIN `fleets_vehicles` fv ON v.id = fv.`vehicleId`
                WHERE v.`plateNumber` = :plateNumber
                AND fv.`fleetId` = :fleetId";

        $stmt = $this->query($sql, ['fleetId' => $fleetId, 'plateNumber' => $plateNumber]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Vehicle::class);
        $row = $stmt->fetch();

        return false === $row ? null : $row;
    }
}