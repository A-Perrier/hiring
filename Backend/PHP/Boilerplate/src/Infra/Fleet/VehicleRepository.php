<?php

namespace Fulll\Infra\Fleet;

use Fulll\Domain\Fleet\Entity\Vehicle;
use Fulll\Infra\AbstractRepository;
use PDO;

class VehicleRepository extends AbstractRepository
{
    /** @var array Vehicle[] */
    private array $collection = [];
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `vehicles` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `plate_number` int(11) NOT NULL,
            `lat` varchar(50) NULL,
            `lng` varchar(50) NULL
        )";

        $this->query($sql);
    }

    public function save(Vehicle $vehicle): void
    {
        $this->collection[] = $vehicle;
    }

    public function findByPlateNumber(string $plateNumber): ?Vehicle
    {
        foreach ($this->collection as $vehicle) {
            if ($vehicle->getPlateNumber() === $plateNumber) {
                return $vehicle;
            }
        }

        return null;
    }
}