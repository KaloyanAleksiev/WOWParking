<?php

namespace App\Traits;


use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait CalculateParkingFeeTrait
{
    /**
     * @param $rates
     * @param $enter_date
     * @param null $exit_date
     * @return array
     * @throws \Exception
     */
    public function calculate($rates, $enter_date, $exit_date = null)
    {
        $dayNightFees = [];
        if (!$exit_date) {
            $exit_date = Carbon::now();
        }

        $startDate = Carbon::parse($enter_date)->format('d-m-Y H:i');
        $endDate = Carbon::parse($exit_date)->format('d-m-Y H:i');

        $periodVehicle = new CarbonPeriod($startDate, $endDate);

        $periodVehicleDays = Carbon::parse($startDate)->daysUntil($endDate);

        foreach ($periodVehicleDays as $key => $date) {
            foreach ($rates as $rate) {
                $rateStartDate = Carbon::parse($date)->format('d-m-Y ' . $rate->from);
                $rateEndDate = Carbon::parse($date)->format('d-m-Y ' . $rate->to);
                if (Carbon::parse($rateStartDate)->greaterThan(Carbon::parse($rateEndDate))){
                    if (!$key) {
                        $previousRateStartDate = Carbon::parse($rateStartDate)->sub(1, 'day')->format('d-m-Y H:i');

                        $periodRate = new CarbonPeriod($previousRateStartDate, $rateEndDate);
                        $floatHours = $this->_calculatePeriodsOverlap($periodVehicle, $periodRate);
                        $dayNightFees[$rate->name]['float_hours'][$key-1] = $floatHours;
                        $dayNightFees[$rate->name]['amount_per_hour'] = $rate->amount_per_hour;
                    }
                    $rateEndDate = Carbon::parse($rateEndDate)->add(1, 'day')->format('d-m-Y H:i');
                }
                $periodRate = new CarbonPeriod($rateStartDate, $rateEndDate);

                $floatHours = $this->_calculatePeriodsOverlap($periodVehicle, $periodRate);
                $dayNightFees[$rate->name]['float_hours'][$key] = $floatHours;
                $dayNightFees[$rate->name]['amount_per_hour'] = $rate->amount_per_hour;
            }
        }

        $fees = [];
        foreach ($dayNightFees as $key => $value) {
            $fees[$key] = number_format(ceil(array_sum($value['float_hours'])) * $value['amount_per_hour'], 2, '.', '');
        }
        $fees['Total'] = number_format(array_sum($fees), 2, '.', '');

        return $fees;
    }

    /**
     * @param CarbonPeriod $periodA
     * @param CarbonPeriod $periodB
     * @return float|int
     */
    private function _calculatePeriodsOverlap(CarbonPeriod $periodA, CarbonPeriod $periodB)
    {
        if (!$periodA->overlaps($periodB)) {
            return 0;
        }

        $firstEndDate = min($periodA->calculateEnd(), $periodB->calculateEnd());
        $latestStartDate = max($periodA->getStartDate(), $periodB->getStartDate());

        return Carbon::parse($firstEndDate)->floatDiffInHours($latestStartDate);
    }
}