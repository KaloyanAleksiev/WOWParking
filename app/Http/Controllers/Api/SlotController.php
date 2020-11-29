<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Slots\SlotRepositoryInterface;
use App\Traits\CalculateParkingFeeTrait;
use Illuminate\Support\Facades\Lang;

class SlotController extends Controller
{
    use CalculateParkingFeeTrait;

    protected $_slotRepository;

    public function __construct(SlotRepositoryInterface $slotRepository)
    {
        $this->_slotRepository = $slotRepository;
    }

    /**
     * Check for free parking slots
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableSlots()
    {
        $freeSlotsCount = config('parking.capacity') - $this->_slotRepository->occupiedSlotsCount();

        return response()->json([
            'status' => 'success',
            'message' => Lang::get('slots.parking_slots'),
            'data' => ['free_slots_count' => $freeSlotsCount]
        ], 200);
    }
}
