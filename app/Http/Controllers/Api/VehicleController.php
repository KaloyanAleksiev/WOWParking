<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Repositories\Interfaces\Rates\RateRepositoryInterface;
use App\Repositories\Interfaces\Slots\SlotRepositoryInterface;
use App\Repositories\Interfaces\Vehicles\VehicleRepositoryInterface;
use App\Traits\CalculateParkingFeeTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    use CalculateParkingFeeTrait;

    protected $_vehicleRepository;
    protected $_rateRepository;
    protected $_slotRepository;

    public function __construct(
        VehicleRepositoryInterface $vehicleRepository,
        RateRepositoryInterface $rateRepository,
        SlotRepositoryInterface $slotRepository
    )
    {
        $this->_vehicleRepository = $vehicleRepository;
        $this->_rateRepository = $rateRepository;
        $this->_slotRepository = $slotRepository;
    }

    public function checkVehicleFees($register_number)
    {
        $registerNumber = preg_replace('/\s+/', '', $register_number);

        if (!empty($registerNumber) && preg_match('/\b[A-Z]{1,2}\d{4}[A-Z]{2}\b/', $registerNumber)) {

            $vehicle = $this->_vehicleRepository->first($registerNumber);
            //check if vehicle exist in the parking system
            if (!$vehicle) {
                return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.no_info')], 404);
            }

            //check if vehicle is at the parking
            if ($vehicle->current_slot_occupied->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.vehicle_not_at_the_parking')], 422);
            }

            //get parking rates
            $rates = $this->_rateRepository->getAllRates(['id', 'name', 'from', 'to', 'amount_per_hour']);

            //calculate vehicle fees
            try {
                $fees = $this->calculate($rates, $vehicle->current_slot_occupied[0]->enter_date, $vehicle->current_slot_occupied[0]->exit_date);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.fee_calculations_error')], 422);
            }

            return response()->json([
                'status' => 'success',
                'message' => Lang::get('vehicles.fees'),
                'data' => ['register_number' => $register_number, 'fees' => $fees]
            ], 200);
        }

        return response()->json(['status' => 'failed', 'message' => Lang::get('vehicles.incorrect_register_number'), 'data' => ['register_number' => $register_number]], 422);
    }

    /**
     * Register vehicle at the parking system
     *
     * @param VehicleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerVehicle(VehicleRequest $request)
    {
        $registerNumber = preg_replace('/\s+/', '', $request->register_number);

        DB::beginTransaction();

        try {
            $vehicle = $this->_vehicleRepository->firstOrCreate($registerNumber);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['register_number' => $registerNumber]);
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.vehicle_registered_failed')], 422);
        }

        //check if vehicle is already at the parking
        if ($vehicle->current_slot_occupied->isNotEmpty()) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.vehicle_already_at_the_parking')], 422);
        }

        //check if there is enough free slots at the parking
        $freeSlotsCount = config('parking.capacity') - $this->_slotRepository->occupiedSlotsCount();
        if ($vehicle->vehicle_type->slots > $freeSlotsCount) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => Lang::get('slots.no_enough_free_slots')], 422);
        }

        try {
            $this->_slotRepository->create($vehicle->id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => Lang::get('slots.slot_occupation_failed')], 422);
        }

        DB::commit();

        return response()->json(['status' => 'success', 'message' => Lang::get('vehicles.vehicle_registered_successfully')]);
    }

    /**
     * Sign out vehicle from the parking system
     *
     * @param VehicleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOutVehicle(VehicleRequest $request)
    {

        $registerNumber = preg_replace('/\s+/', '', $request->register_number);

        $vehicle = $this->_vehicleRepository->first($registerNumber);
        //check if vehicle exist in the parking system
        if (!$vehicle) {
            return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.no_info')], 404);
        }

        //check if vehicle is at the parking
        if ($vehicle->current_slot_occupied->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.vehicle_not_at_the_parking')], 422);
        }

        //get parking rates
        $rates = $this->_rateRepository->getAllRates(['id', 'name', 'from', 'to', 'amount_per_hour']);

        //calculate fees
        try {
            $fees = $this->calculate($rates, $vehicle->current_slot_occupied[0]->enter_date, $vehicle->current_slot_occupied[0]->exit_date);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => Lang::get('vehicles.fee_calculations_error')], 422);
        }

        try {
            $this->_slotRepository->update($vehicle->current_slot_occupied[0]->id, $fees['Total']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => Lang::get('slots.free_slot_failed')], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => Lang::get('vehicles.vehicle_sign_out_successfully'),
            'data' => ['register_number' => $request->register_number, 'fees' => $fees]
        ], 200);
    }
}
