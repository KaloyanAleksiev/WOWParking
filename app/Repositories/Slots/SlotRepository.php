<?php

namespace App\Repositories\Slots;


use App\Models\Slot;
use App\Repositories\Interfaces\Slots\SlotRepositoryInterface;
use Carbon\Carbon;

class SlotRepository implements SlotRepositoryInterface
{
    protected $model;

    public function __construct(Slot $slot)
    {
        $this->model = $slot;
    }

    /**
     * Create Slot
     *
     * @param $vehicle_id
     * @return mixed
     */
    public function create($vehicle_id)
    {
        $slot = Slot::create(['vehicle_id' => $vehicle_id, 'enter_date' => Carbon::now()]);

        $slot->save();

        return $slot;
    }

    /**
     * Update slot
     *
     * @param $slot_id
     * @param $amount
     * @return Slot
     */
    public function update($slot_id, $amount)
    {
        $slot = Slot::findOrFail($slot_id);
        $slot->update(['exit_date' => Carbon::now(), 'free' => 1, 'amount' => $amount]);
        $slot->save();

        return  $slot;
    }

    /**
     * Get count of occupied slots
     *
     * @return mixed
     */
    public function occupiedSlotsCount()
    {
        return $this->model->where('free', 0)->with('vehicle','vehicle.vehicle_type')->get()->sum('vehicle.vehicle_type.slots');
    }

}