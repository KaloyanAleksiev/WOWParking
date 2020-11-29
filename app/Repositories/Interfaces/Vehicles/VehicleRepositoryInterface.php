<?php

namespace App\Repositories\Interfaces\Vehicles;


use App\Models\Vehicle;

interface VehicleRepositoryInterface
{
    public function __construct(Vehicle $vehicle);

    public function firstOrCreate($register_number): Vehicle;

    public function first($register_number): Vehicle;
}