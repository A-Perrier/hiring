<?php

namespace Fulll\Infra\Fleet\Repository;

use Fulll\Infra\AbstractRepository;
use PDO;

class FleetVehicleRepository extends AbstractRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `fleets_vehicles` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fleetId` int(11) NOT NULL,
            `vehicleId` int(11) NOT NULL,
            FOREIGN KEY (`fleetId`) REFERENCES `fleets` (`id`),
            FOREIGN KEY (`vehicleId`) REFERENCES `vehicles` (`id`)
        )";

        $this->query($sql);
    }
}