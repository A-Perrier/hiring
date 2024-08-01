<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Fulll\Domain\Fleet\Entity\User;
use Fulll\Domain\Fleet\Entity\Vehicle;
use Fulll\Domain\Fleet\Exception\VehicleAlreadyParkedAtLocationException;
use Fulll\Domain\Fleet\Exception\VehicleAlreadyRegisteredException;
use Fulll\Domain\Fleet\Exception\VehicleNotFoundAtLocationException;
use Fulll\Domain\Fleet\Exception\VehicleNotRegisteredInFleetException;
use Fulll\Domain\Fleet\ValueObject\Location;
use Fulll\Domain\Fleet\ValueObject\VehiclePlateNumber;

class FeatureContext implements Context
{
    private array $users = [];
    private array $vehicles = [];
    private array $locations = [];
    private null|VehicleAlreadyRegisteredException|VehicleAlreadyParkedAtLocationException $error = null;
    private string $activeUserId = '1';


    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $mainUser = new User('1');
        $this->users[] = $mainUser;

        return $mainUser->getFleet();
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $vehicle = new Vehicle(
            new VehiclePlateNumber('XX-123-XX')
        );

        $this->vehicles[] = $vehicle;
        return $vehicle;
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        $user = array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId)[0];
        $vehicle = array_filter($this->vehicles, fn($vehicle) => $vehicle->getPlateNumber() === 'XX-123-XX')[0];
        $user->getFleet()->addVehicle($vehicle);
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $location = new Location('-24', '-75');
        $this->locations[] = $location;
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicle = $user->getFleet()->getVehicles()[0];
        $vehicle->setLocation($this->locations[0]);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicle = $user->getFleet()->localizeVehicle($this->locations[0]);

        if ($vehicle === null) {
            throw new VehicleNotFoundAtLocationException();
        }
    }

    /**
     * @Given my vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicle = $user->getFleet()->getVehicles()[0];
        $vehicle->setLocation($this->locations[0]);
        $vehicle = $user->getFleet()->localizeVehicle($this->locations[0]);

        if ($vehicle === null) {
            throw new VehicleNotFoundAtLocationException();
        }
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation()
    {
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicle = $user->getFleet()->localizeVehicle($this->locations[0]);

        try {
            $vehicle->setLocation($this->locations[0]);
        } catch (VehicleAlreadyParkedAtLocationException $e) {
            $this->error = $e;
        }

    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        if (!$this->error instanceof VehicleAlreadyParkedAtLocationException) {
            throw new Exception('The user has not been notified its vehicle is already parked at this location');
        }
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet()
    {
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicle = array_filter($this->vehicles, fn($vehicle) => $vehicle->getPlateNumber() === 'XX-123-XX')[0];

        $user->getFleet()->addVehicle($vehicle);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $testedPlateNumber = 'XX-123-XX';
        $user = array_values(array_filter($this->users, fn($user) => $user->getId() === $this->activeUserId))[0];
        $vehicles = $user->getFleet()->getVehicles();

        foreach ($vehicles as $vehicle) {
            if ($vehicle->getPlateNumber() === $testedPlateNumber) {
                return true;
            }
        }


        throw new VehicleNotRegisteredInFleetException(vehiclePlateNumber: $testedPlateNumber);
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet()
    {
        $user = array_filter($this->users, fn($user) => $user->getId() === '1')[0];
        $vehicle = array_values(array_filter($this->vehicles, fn($vehicle) => $vehicle->getPlateNumber() === 'XX-123-XX'))[0];
        try {
            $user->getFleet()->addVehicle($vehicle);
        } catch (VehicleAlreadyRegisteredException $e) {
            $this->error = $e;
        }
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet()
    {
        if (!$this->error instanceof VehicleAlreadyRegisteredException) {
            throw new Exception('The user has not been notified its vehicle is already registered');
        }
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $secondUser = new User('2');
        $this->users[] = $secondUser;

        return $secondUser->getFleet();
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $testedPlateNumber = 'XX-123-XX';
        $anotherUser = array_filter($this->users, fn($user) => $user->getId() === '1')[0];
        $vehicle = array_filter($this->vehicles, fn($vehicle) => $vehicle->getPlateNumber() === $testedPlateNumber)[0];
        $anotherUser->getFleet()->addVehicle($vehicle);
        $this->activeUserId = '2';
    }
}
