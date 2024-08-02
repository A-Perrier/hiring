<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\Domain\Fleet\Entity\User;
use Fulll\Infra\Database;
use Fulll\Infra\Fleet\Exception\FleetNotFoundException;
use Fulll\Infra\Fleet\Exception\VehicleNotFoundException;
use Fulll\Infra\Fleet\Repository\UserRepository;
use Fulll\Infra\Fleet\Repository\VehicleRepository;

class FeatureContext implements Context
{
    private string $output;
    private PDO $pdo;
    private UserRepository $userRepository;
    private VehicleRepository $vehicleRepository;
    private User $mainUser;
    private User $secondUser;
    private array $location = [
        'lat' => '54',
        'lng' => '102'
    ];
    private string $samplePlateNumber = 'XX-123-XR';


    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $userId = 1;
        $this->pdo = (new Database())->login();
        $this->userRepository = new UserRepository($this->pdo);
        $this->output = shell_exec("php ./fleet create $userId");
        $this->mainUser = $this->userRepository->findById($userId);

        if ($this->mainUser->getFleetId() === null) {
            throw new FleetNotFoundException();
        }
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->vehicleRepository = new VehicleRepository($this->pdo);
        $vehicle = $this->vehicleRepository->findByPlateNumber($this->samplePlateNumber);

        if ($vehicle === null) {
            throw new VehicleNotFoundException();
        }
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        try {
            $this->output = shell_exec("php ./fleet register-vehicle {$this->mainUser->getFleetId()} $this->samplePlateNumber");
        } catch (Throwable $e) {
            // Making sure that any Error thrown results as a test failure
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        return $this->location;
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        try {
            $this->output = shell_exec("php ./fleet localize-vehicle {$this->mainUser->getFleetId()} $this->samplePlateNumber --lat={$this->location['lat']} --lng={$this->location['lng']}");
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        if (strpos($this->output, 'This vehicle has been moved to the provided location') === false) {
            throw new Exception('The user has not been notified its vehicle has been moved');
        }
    }

    /**
     * @Given my vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        $vehicle = $this->vehicleRepository->findByFleetIdAndPlateNumber($this->mainUser->getFleetId(), $this->samplePlateNumber);

        if ($vehicle === null) {
            throw new VehicleNotFoundException();
        }

        if (($vehicle->getLat() !== $this->location['lat']) && ($vehicle->getLng() !== $this->location['lng'])) {
            throw new VehicleNotFoundException();
        }
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation()
    {
        try {
            $this->output = shell_exec("php ./fleet localize-vehicle {$this->mainUser->getFleetId()} $this->samplePlateNumber --lat={$this->location['lat']} --lng={$this->location['lng']}");
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        if (strpos($this->output, 'This vehicle is already parked at this location') === false) {
            throw new Exception('The user has not been notified its vehicle is already parked at this location');
        }
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet()
    {
        try {
            $this->output = shell_exec("php ./fleet register-vehicle {$this->mainUser->getFleetId()} $this->samplePlateNumber");
        } catch (Throwable $e) {
            // Making sure that any Error thrown results as a test failure
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $vehicle = $this->vehicleRepository->findByFleetIdAndPlateNumber($this->mainUser->getFleetId(), $this->samplePlateNumber);

        if ($vehicle === null) {
            throw new VehicleNotFoundException();
        }
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet()
    {
        try {
            $this->output = shell_exec("php ./fleet register-vehicle {$this->mainUser->getFleetId()} $this->samplePlateNumber");
        } catch (Throwable $e) {
            // Making sure that any Error thrown results as a test failure
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet()
    {
        if (strpos($this->output, 'This vehicle already belongs to this fleet') === false) {
            throw new Exception('The user has not been notified its vehicle is already registered');
        }
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $userId = 2;
        $this->pdo = (new Database())->login();
        $this->userRepository = new UserRepository($this->pdo);
        $this->output = shell_exec("php ./fleet create $userId");
        $this->secondUser = $this->userRepository->findById($userId);

        if ($this->secondUser->getFleetId() === null) {
            throw new FleetNotFoundException();
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        try {
            $this->output = shell_exec("php ./fleet register-vehicle {$this->secondUser->getFleetId()} $this->samplePlateNumber");
        } catch (Throwable $e) {
            // Making sure that any Error thrown results as a test failure
            throw new Exception($e->getMessage());
        }
    }
}
