<?php

namespace App\Repositories\Vehicles;


use App\Models\Vehicle;
use App\Repositories\Interfaces\Vehicles\VehicleRepositoryInterface;

class VehicleRepository implements VehicleRepositoryInterface
{
    protected $model;

    public function __construct(Vehicle $vehicle)
    {
        $this->model = $vehicle;
    }

    /**
     * @param $register_number
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate($register_number): Vehicle
    {
        return Vehicle::with('current_slot_occupied')->firstOrCreate(['register_number' => $register_number], ['vehicle_type_id' => 1]);
    }

    /**
     * @param $register_number
     * @return Vehicle
     */
    public function first($register_number): Vehicle
    {
        return $this->model->where('register_number', $register_number)->with('current_slot_occupied')->first();
    }

}