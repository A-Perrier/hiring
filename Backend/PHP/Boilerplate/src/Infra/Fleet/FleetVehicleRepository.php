<?php

namespace Fulll\Infra\Fleet;

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
            `fleet_id` int(11) NOT NULL,
            `vehicle_id` int(11) NOT NULL,
            FOREIGN KEY (`fleet_id`) REFERENCES `fleets` (`id`),
            FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`)
        )";

        $this->query($sql);
    }
}