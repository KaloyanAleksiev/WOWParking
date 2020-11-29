<?php

namespace App\Repositories\Interfaces\Slots;


use App\Models\Slot;

interface SlotRepositoryInterface
{
    public function __construct(Slot $slot);

    public function create($vehicle_id);

    public function update($slot_id, $amount);

    public function occupiedSlotsCount();

}